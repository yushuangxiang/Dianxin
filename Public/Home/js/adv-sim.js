/*
 *
 * @adv
 * @auther sean.wang
 * @version 5.7.2
 * */
(function(win, doc, enc) {
    /*
     *
     * @function getHostName
     * @获取当前主域名
     * @return String
     * */
    var getHostName = function(){
        var hostName = location.hostname;
        var reg = /^\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}$/;
        var hosts=hostName.split(".");
        var e = hosts.length-2;
        if(reg.test(hostName) || 2===hosts.length){
            return hostName;
        }
        for (;0 <= e; --e) {
            if ("www" === hosts[e]) {
                return hosts.slice(e + 1).join(".");
            }
            if (-1 === ",com,net,org,gov,edu,info,name,int,mil,arpa,asia,biz,pro,coop,aero,museum,ac,ad,ae,af,ag,ai,al,am,an,ao,aq,ar,as,at,au,aw,az,ba,bb,bd,be,bf,bg,bh,bi,bj,bm,bn,bo,br,bs,bt,bv,bw,by,bz,ca,cc,cf,cg,ch,ci,ck,cl,cm,cn,co,cq,cr,cu,cv,cx,cy,cz,de,dj,dk,dm,do,dz,ec,ee,eg,eh,es,et,ev,fi,fj,fk,fm,fo,fr,ga,gb,gd,ge,gf,gh,gi,gl,gm,gn,gp,gr,gt,gu,gw,gy,hk,hm,hn,hr,ht,hu,id,ie,il,in,io,iq,ir,is,it,jm,jo,jp,ke,kg,kh,ki,km,kn,kp,kr,kw,ky,kz,la,lb,lc,li,lk,lr,ls,lt,lu,lv,ly,ma,mc,md,me,mg,mh,ml,mm,mn,mo,mp,mq,mr,ms,mt,mv,mw,mx,my,mz,na,nc,ne,nf,ng,ni,nl,no,np,nr,nt,nu,nz,om,pa,pe,pf,pg,ph,pk,pl,pm,pn,pr,pt,pw,py,qa,re,ro,ru,rw,sa,sb,sc,sd,se,sg,sh,si,sj,sk,sl,sm,sn,so,sr,st,su,sy,sz,tc,td,tf,tg,th,tj,tk,tm,tn,to,tp,tr,tt,tv,tw,tz,ua,ug,uk,us,uy,va,vc,ve,vg,vn,vu,wf,ws,ye,yu,za,zm,zr,zw,".indexOf("," + hosts[e] + ",")) {
                return hosts.slice(e).join(".");
            }
        }
        return hostName;
    }

    /*
     * @function _py.getLast 获取最后一次参数
     * @function return String
     * */
    _py.getLast = function(e) {
        for (var a = this.length - 1; 0 <= a; a--) {
            if (this[a][0] == e) {
                return this[a][1];
            }
        }
    };

    /*
     * @function _py.serialize 组合所有需发送参数
     * @return String
     * */
    _py.serialize = function() {
        function t(v, e) {
            for (var u = 0; u < v.length; u++) {
                if (v[u] === e) {
                    return u;
                }
            }
            return -1;
        }
        for (var i = [ "domain","urlParam",'pi','e','p','mapping'], m = [], j = [], n = [], k, l = 0; l < this.length; l++) {
            k = this[l][0], -1 === t(i, k) && (j[k] = j[k] || [], 0 < j[k].length ? -1 === t(j[k], this[l][1]) && j[k].push(this[l][1]) : (j[k].push(this[l][1]),
                m.push([ k, j[k] ])));
        }
        for (l = 0; l < m.length; l++) {
            n.push(m[l][0] + "=" + enc(m[l][1].join(",")));
        }
        return n.join("&");
    };

    /*
     * @Object ipy共用方法用来接收参数
     * */
    win.ipy = {
        r: /(^|&)jump=(\d*)/i,
        cookie: {
            set: function(n, j, k, m, l) {
                z = new Date();
                z.setTime(z.getTime() + (k || 0));
                doc.cookie = n + "=" + enc(j || "") + (k ? "; expires=" + z.toGMTString() : "") + ";path=/; domain=" + (m || location.host) + (l ? "; secure" : "");
            },
            get: function(a) {
                return (a = doc.cookie.match(RegExp("(^|;)\\s*" + a + "=([^;]*)", "i"))) ? decodeURIComponent(a[2]) : "";
            }
        },
        setCookie: function(e, b) {
            ipy.cookie.set(e, b, 31536e6, getHostName());
        },
        setSession: function(e, b) {
            ipy.cookie.set(e, b, 0, getHostName());
        },
        getJump: function() {
            var b = ipy.cookie.get("ipysession");
            return b && (b = b.match(ipy.r)) ? parseInt(b[2]) : 0;
        },
        setJump: function(i) {
            var e = ipy.cookie.get("ipysession");
            e ? e.match(ipy.r) ? ipy.setSession("ipysession", e.replace(/jump=(\d*)/, "jump=" + i)) : ipy.setSession("ipysession", e + "&jump=" + i) : ipy.setSession("ipysession", "jump=" + i);
        },
        getInfo:function(n){
            var v= ipy.cookie.get(n);
            if(v){
                return v;
            };
            try{
                if(win.localStorage){
                    if(localStorage.getItem(n)){
                        return localStorage.getItem(n);
                    }
                }
            }catch (e){}
            return "";
        },
        setInfo:function(n,v){
            if(v ==null || v == ""){return}
            ipy.setCookie(n,v);
            try{
                if(win.localStorage){
                    localStorage.setItem(n,v);
                }
            }catch(e){}

        },
        getQueryString:function(name){
            if (name== '' || name ==null) {
                return;
            };
            var _u =  win.location.href,
                _p = _u.split(name),
                dis = "";
            if(_p.length>1){
                _u = _p[1];
                dis = _u.split("&")[0].replace("=","");
                return dis;
            }
            var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)"),
                r = win.location.search.substr(1).match(reg);
            if(r!=null && r){
                return r[2];
            };
            var _h= win.location.hash.substr(1).match(reg);
            if(_h!=null && _h){
                return _h[2];
            }
            return '';
        },
        setExendParam:function(p,c,e){
            var _pi = p || "",
                _cg= c || "",
                _ed = e || "";
            ipy.getExtendParam(_pi,_cg,_ed);
        },
        getExtendParam:function(i,g,c){
            var e="",pv="";
            if (i !=null && i) {
                e = "p="+i;
            };
            if (g !=null && g) {
                _py.push(['pv',g]);
            };
            if (c !=null && c) {
                e += "&ext="+c;
            };
            ipy.extendSend(e);
        },
        itemInfo:function(json){
            var v = [],c;
            switch(typeof json){
                case 'string':
                    c=json;
                    break;
                case 'object':
                    var data = ['id','name','origPrice','price','brand','imgUrl','productUrl','categoryId','category','promotion','discount','soldOut','domain'];
                    for (var i = 0; i < data.length; i++) {
                        var str = (json[data[i]] == undefined)?'':json[data[i]];
                        str = str.toString();
                        v.push(enc(str));
                    };
                    ipy.id = json.id || '';
                    c = v.join(',');
                    break;
                default:
                    return c = '';
            }
            return c;
        },
        extendSend:function(ex){
            var e='';
            if (_py.getLast("e")) {
                e='e='+_py.getLast("e")+'&';
            };
            e += ex,s = _py.getLast("domain"),
                p = ("https:" == location.protocol ? "https" : "http") + "://" + s + "/adv?" + _py.serialize() +ipy.getSession()+"&e="+h(e)+"&rd=" + new Date().getTime();
            (new Image()).src=p;
        },
        getSession:function(){
            var c = _py.getLast("c");
            if (c && c != null) {
                var j = ipy.getJump();
                if (!isNaN(j) && j == 0) {
                    ipy.setJump(j+1);
                    return '';
                };
                j++;
                ipy.setJump(j);
                return "&s="+j;
            };
            return '';
        },
        getP:function(){
            var p = _py.getLast('p');
            var id = ipy.id ? ipy.id : '';
            p = p ? p : id;
            return p;
        }
    };

    /*
     * 延迟发送cookie Mapping
     * */
    if(!_py.getLast("mapping")){
        var f = "setTimeout(function() {var f=document;e = f.createElement('iframe');e.src='" + ("http:" != location.protocol ? "https://cm.ipinyou.com/cmas.html" : "http://cm.ipinyou.com/cma.html") + "';f.body.insertBefore(e,f.body.firstChild);e.style.display='none';}, 5000)";
    }

    var p = location.href, q = doc.referrer,e,pi,id;
    win.parent != win && (p = q, q = "");
    p && _py.push([ "u", p ]);
    q && _py.push([ "r", q ]);
    var u = _py.getLast('urlParam') || "pyck",d = ipy.getQueryString(u);
    d = d ? d:ipy.getInfo("ipycookie");
    ipy.setInfo('ipycookie',d);
    d && _py.push([ "c", d ]);
    s = _py.getLast("domain");
    e= _py.getLast("e");
    if (e != '' && e) {
        e='e='+_py.getLast("e");
    }else{
        e='';
    };
    //pi 用于商品库对接
    pi = ipy.itemInfo(_py.getLast("pi"));
    p = ("https:" == location.protocol ? "https" : "http") + "://" + s + "/adv?" + _py.serialize() +ipy.getSession()+"&pi="+enc(pi)+"&p="+enc(ipy.getP())+"&e="+enc(e)+"&rd=" + new Date().getTime();
    q = doc.createElement("iframe");
    q.src = "javascript:false;";
    q.style.display = "none";

    function adv(){
        new Image().src = p;
    }
    setTimeout(adv,10);
})(window, document, encodeURIComponent);