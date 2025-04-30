vcl 4.1;

import std;

backend default {
    .host = "nginx";
    .port = "80";
}

acl purge {
    "0.0.0.0"/0;
    "::"/0;
}

sub vcl_recv {
    if (req.url ~ "\?$") {
        set req.url = regsub(req.url, "\?$", "");
    }

    set req.http.Host = regsub(req.http.Host, ":[0-9]+", "");

    set req.url = std.querysort(req.url);

    unset req.http.proxy;

    if(req.method == "PURGE") {
        if(!client.ip ~ purge) {
            return(synth(405,"PURGE not allowed for this IP address"));
        }

        ban("req.url ~ .");
        return (synth(200, "Purged all cache"));
    }

    if (
        req.method != "GET" &&
        req.method != "HEAD" &&
        req.method != "PUT" &&
        req.method != "POST" &&
        req.method != "PATCH" &&
        req.method != "TRACE" &&
        req.method != "OPTIONS" &&
        req.method != "DELETE"
    ) {
        return (pipe);
    }

    # Only cache GET and HEAD requests
    if (req.method != "GET" && req.method != "HEAD") {
        set req.http.X-Cacheable = "NO:REQUEST-METHOD";
        return(pass);
    }

    if (
        req.url == "/" ||
        req.url == "/without-varnish"
    ) {
	     set req.http.X-Cacheable = "NO:Logged in/Got Sessions";
	     if(req.http.X-Requested-With == "XMLHttpRequest") {
		     set req.http.X-Cacheable = "NO:Ajax";
	     }
        return(pass);
    }

    unset req.http.Cookie;
    return(hash);
}

sub vcl_hash {
    if(req.http.X-Forwarded-Proto) {
        hash_data(req.http.X-Forwarded-Proto);
    }
}

sub vcl_backend_response {
    set beresp.http.x-url = bereq.url;
    set beresp.http.x-host = bereq.http.host;

    if (!beresp.http.Cache-Control) {
        set beresp.ttl = 1h;
        set beresp.http.X-Cacheable = "YES:Forced";
    }

    if (bereq.http.X-Static-File == "true") {
        unset beresp.http.Set-Cookie;
        set beresp.http.X-Cacheable = "YES:Forced";
        set beresp.ttl = 1d;
    }

    if (beresp.http.Set-Cookie) {
        set beresp.http.X-Cacheable = "NO:Got Cookies";
    } elseif(beresp.http.Cache-Control ~ "private") {
        set beresp.http.X-Cacheable = "NO:Cache-Control=private";
    }
}

sub vcl_deliver {
    if(req.http.X-Cacheable) {
        set resp.http.X-Cacheable = req.http.X-Cacheable;
    } elseif(obj.uncacheable) {
        if(!resp.http.X-Cacheable) {
            set resp.http.X-Cacheable = "NO:UNCACHEABLE";
        }
    } elseif(!resp.http.X-Cacheable) {
        set resp.http.X-Cacheable = "YES";
    }

    unset resp.http.x-url;
    unset resp.http.x-host;
}

