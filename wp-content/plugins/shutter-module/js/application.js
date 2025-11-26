/*!
 * jQuery JavaScript Library v1.11.1
 * http://jquery.com/
 *
 * Includes Sizzle.js
 * http://sizzlejs.com/
 *
 * Copyright 2005, 2014 jQuery Foundation, Inc. and other contributors
 * Released under the MIT license
 * http://jquery.org/license
 *
 * Date: 2014-05-01T17:42Z
 */
! function (e, t) {
    "object" == typeof module && "object" == typeof module.exports ? module.exports = e.document ? t(e, !0) : function (e) {
        if (!e.document) throw new Error("jQuery requires a window with a document");
        return t(e)
    } : t(e)
}("undefined" != typeof window ? window : this, function (e, t) {
    function i(e) {
        var t = e.length,
            i = st.type(e);
        return "function" === i || st.isWindow(e) ? !1 : 1 === e.nodeType && t ? !0 : "array" === i || 0 === t || "number" == typeof t && t > 0 && t - 1 in e
    }

    function n(e, t, i) {
        if (st.isFunction(t)) return st.grep(e, function (e, n) {
            return !!t.call(e, n, e) !== i
        });
        if (t.nodeType) return st.grep(e, function (e) {
            return e === t !== i
        });
        if ("string" == typeof t) {
            if (dt.test(t)) return st.filter(t, e, i);
            t = st.filter(t, e)
        }
        return st.grep(e, function (e) {
            return st.inArray(e, t) >= 0 !== i
        })
    }

    function s(e, t) {
        do e = e[t]; while (e && 1 !== e.nodeType);
        return e
    }

    function r(e) {
        var t = wt[e] = {};
        return st.each(e.match(bt) || [], function (e, i) {
            t[i] = !0
        }), t
    }

    function a() {
        ft.addEventListener ? (ft.removeEventListener("DOMContentLoaded", o, !1), e.removeEventListener("load", o, !1)) : (ft.detachEvent("onreadystatechange", o), e.detachEvent("onload", o))
    }

    function o() {
        (ft.addEventListener || "load" === event.type || "complete" === ft.readyState) && (a(), st.ready())
    }

    function l(e, t, i) {
        if (void 0 === i && 1 === e.nodeType) {
            var n = "data-" + t.replace(Dt, "-$1").toLowerCase();
            if (i = e.getAttribute(n), "string" == typeof i) {
                try {
                    i = "true" === i ? !0 : "false" === i ? !1 : "null" === i ? null : +i + "" === i ? +i : Ct.test(i) ? st.parseJSON(i) : i
                } catch (s) {}
                st.data(e, t, i)
            } else i = void 0
        }
        return i
    }

    function c(e) {
        var t;
        for (t in e)
            if (("data" !== t || !st.isEmptyObject(e[t])) && "toJSON" !== t) return !1;
        return !0
    }

    function u(e, t, i, n) {
        if (st.acceptData(e)) {
            var s, r, a = st.expando,
                o = e.nodeType,
                l = o ? st.cache : e,
                c = o ? e[a] : e[a] && a;
            if (c && l[c] && (n || l[c].data) || void 0 !== i || "string" != typeof t) return c || (c = o ? e[a] = V.pop() || st.guid++ : a), l[c] || (l[c] = o ? {} : {
                "toJSON": st.noop
            }), ("object" == typeof t || "function" == typeof t) && (n ? l[c] = st.extend(l[c], t) : l[c].data = st.extend(l[c].data, t)), r = l[c], n || (r.data || (r.data = {}), r = r.data), void 0 !== i && (r[st.camelCase(t)] = i), "string" == typeof t ? (s = r[t], null == s && (s = r[st.camelCase(t)])) : s = r, s
        }
    }

    function h(e, t, i) {
        if (st.acceptData(e)) {
            var n, s, r = e.nodeType,
                a = r ? st.cache : e,
                o = r ? e[st.expando] : st.expando;
            if (a[o]) {
                if (t && (n = i ? a[o] : a[o].data)) {
                    st.isArray(t) ? t = t.concat(st.map(t, st.camelCase)) : t in n ? t = [t] : (t = st.camelCase(t), t = t in n ? [t] : t.split(" ")), s = t.length;
                    for (; s--;) delete n[t[s]];
                    if (i ? !c(n) : !st.isEmptyObject(n)) return
                }(i || (delete a[o].data, c(a[o]))) && (r ? st.cleanData([e], !0) : it.deleteExpando || a != a.window ? delete a[o] : a[o] = null)
            }
        }
    }

    function d() {
        return !0
    }

    function p() {
        return !1
    }

    function f() {
        try {
            return ft.activeElement
        } catch (e) {}
    }

    function m(e) {
        var t = Lt.split("|"),
            i = e.createDocumentFragment();
        if (i.createElement)
            for (; t.length;) i.createElement(t.pop());
        return i
    }

    function g(e, t) {
        var i, n, s = 0,
            r = typeof e.getElementsByTagName !== _t ? e.getElementsByTagName(t || "*") : typeof e.querySelectorAll !== _t ? e.querySelectorAll(t || "*") : void 0;
        if (!r)
            for (r = [], i = e.childNodes || e; null != (n = i[s]); s++) !t || st.nodeName(n, t) ? r.push(n) : st.merge(r, g(n, t));
        return void 0 === t || t && st.nodeName(e, t) ? st.merge([e], r) : r
    }

    function v(e) {
        Nt.test(e.type) && (e.defaultChecked = e.checked)
    }

    function y(e, t) {
        return st.nodeName(e, "table") && st.nodeName(11 !== t.nodeType ? t : t.firstChild, "tr") ? e.getElementsByTagName("tbody")[0] || e.appendChild(e.ownerDocument.createElement("tbody")) : e
    }

    function b(e) {
        return e.type = (null !== st.find.attr(e, "type")) + "/" + e.type, e
    }

    function w(e) {
        var t = $t.exec(e.type);
        return t ? e.type = t[1] : e.removeAttribute("type"), e
    }

    function x(e, t) {
        for (var i, n = 0; null != (i = e[n]); n++) st._data(i, "globalEval", !t || st._data(t[n], "globalEval"))
    }

    function k(e, t) {
        if (1 === t.nodeType && st.hasData(e)) {
            var i, n, s, r = st._data(e),
                a = st._data(t, r),
                o = r.events;
            if (o) {
                delete a.handle, a.events = {};
                for (i in o)
                    for (n = 0, s = o[i].length; s > n; n++) st.event.add(t, i, o[i][n])
            }
            a.data && (a.data = st.extend({}, a.data))
        }
    }

    function _(e, t) {
        var i, n, s;
        if (1 === t.nodeType) {
            if (i = t.nodeName.toLowerCase(), !it.noCloneEvent && t[st.expando]) {
                s = st._data(t);
                for (n in s.events) st.removeEvent(t, n, s.handle);
                t.removeAttribute(st.expando)
            }
            "script" === i && t.text !== e.text ? (b(t).text = e.text, w(t)) : "object" === i ? (t.parentNode && (t.outerHTML = e.outerHTML), it.html5Clone && e.innerHTML && !st.trim(t.innerHTML) && (t.innerHTML = e.innerHTML)) : "input" === i && Nt.test(e.type) ? (t.defaultChecked = t.checked = e.checked, t.value !== e.value && (t.value = e.value)) : "option" === i ? t.defaultSelected = t.selected = e.defaultSelected : ("input" === i || "textarea" === i) && (t.defaultValue = e.defaultValue)
        }
    }

    function C(t, i) {
        var n, s = st(i.createElement(t)).appendTo(i.body),
            r = e.getDefaultComputedStyle && (n = e.getDefaultComputedStyle(s[0])) ? n.display : st.css(s[0], "display");
        return s.detach(), r
    }

    function D(e) {
        var t = ft,
            i = Zt[e];
        return i || (i = C(e, t), "none" !== i && i || (Qt = (Qt || st("<iframe frameborder='0' width='0' height='0'/>")).appendTo(t.documentElement), t = (Qt[0].contentWindow || Qt[0].contentDocument).document, t.write(), t.close(), i = C(e, t), Qt.detach()), Zt[e] = i), i
    }

    function S(e, t) {
        return {
            "get": function () {
                var i = e();
                if (null != i) return i ? void delete this.get : (this.get = t).apply(this, arguments)
            }
        }
    }

    function T(e, t) {
        if (t in e) return t;
        for (var i = t.charAt(0).toUpperCase() + t.slice(1), n = t, s = di.length; s--;)
            if (t = di[s] + i, t in e) return t;
        return n
    }

    function E(e, t) {
        for (var i, n, s, r = [], a = 0, o = e.length; o > a; a++) n = e[a], n.style && (r[a] = st._data(n, "olddisplay"), i = n.style.display, t ? (r[a] || "none" !== i || (n.style.display = ""), "" === n.style.display && Et(n) && (r[a] = st._data(n, "olddisplay", D(n.nodeName)))) : (s = Et(n), (i && "none" !== i || !s) && st._data(n, "olddisplay", s ? i : st.css(n, "display"))));
        for (a = 0; o > a; a++) n = e[a], n.style && (t && "none" !== n.style.display && "" !== n.style.display || (n.style.display = t ? r[a] || "" : "none"));
        return e
    }

    function M(e, t, i) {
        var n = li.exec(t);
        return n ? Math.max(0, n[1] - (i || 0)) + (n[2] || "px") : t
    }

    function N(e, t, i, n, s) {
        for (var r = i === (n ? "border" : "content") ? 4 : "width" === t ? 1 : 0, a = 0; 4 > r; r += 2) "margin" === i && (a += st.css(e, i + Tt[r], !0, s)), n ? ("content" === i && (a -= st.css(e, "padding" + Tt[r], !0, s)), "margin" !== i && (a -= st.css(e, "border" + Tt[r] + "Width", !0, s))) : (a += st.css(e, "padding" + Tt[r], !0, s), "padding" !== i && (a += st.css(e, "border" + Tt[r] + "Width", !0, s)));
        return a
    }

    function A(e, t, i) {
        var n = !0,
            s = "width" === t ? e.offsetWidth : e.offsetHeight,
            r = ei(e),
            a = it.boxSizing && "border-box" === st.css(e, "boxSizing", !1, r);
        if (0 >= s || null == s) {
            if (s = ti(e, t, r), (0 > s || null == s) && (s = e.style[t]), ni.test(s)) return s;
            n = a && (it.boxSizingReliable() || s === e.style[t]), s = parseFloat(s) || 0
        }
        return s + N(e, t, i || (a ? "border" : "content"), n, r) + "px"
    }

    function I(e, t, i, n, s) {
        return new I.prototype.init(e, t, i, n, s)
    }

    function j() {
        return setTimeout(function () {
            pi = void 0
        }), pi = st.now()
    }

    function O(e, t) {
        var i, n = {
                "height": e
            },
            s = 0;
        for (t = t ? 1 : 0; 4 > s; s += 2 - t) i = Tt[s], n["margin" + i] = n["padding" + i] = e;
        return t && (n.opacity = n.width = e), n
    }

    function F(e, t, i) {
        for (var n, s = (bi[t] || []).concat(bi["*"]), r = 0, a = s.length; a > r; r++)
            if (n = s[r].call(i, t, e)) return n
    }

    function L(e, t, i) {
        var n, s, r, a, o, l, c, u, h = this,
            d = {},
            p = e.style,
            f = e.nodeType && Et(e),
            m = st._data(e, "fxshow");
        i.queue || (o = st._queueHooks(e, "fx"), null == o.unqueued && (o.unqueued = 0, l = o.empty.fire, o.empty.fire = function () {
            o.unqueued || l()
        }), o.unqueued++, h.always(function () {
            h.always(function () {
                o.unqueued--, st.queue(e, "fx").length || o.empty.fire()
            })
        })), 1 === e.nodeType && ("height" in t || "width" in t) && (i.overflow = [p.overflow, p.overflowX, p.overflowY], c = st.css(e, "display"), u = "none" === c ? st._data(e, "olddisplay") || D(e.nodeName) : c, "inline" === u && "none" === st.css(e, "float") && (it.inlineBlockNeedsLayout && "inline" !== D(e.nodeName) ? p.zoom = 1 : p.display = "inline-block")), i.overflow && (p.overflow = "hidden", it.shrinkWrapBlocks() || h.always(function () {
            p.overflow = i.overflow[0], p.overflowX = i.overflow[1], p.overflowY = i.overflow[2]
        }));
        for (n in t)
            if (s = t[n], mi.exec(s)) {
                if (delete t[n], r = r || "toggle" === s, s === (f ? "hide" : "show")) {
                    if ("show" !== s || !m || void 0 === m[n]) continue;
                    f = !0
                }
                d[n] = m && m[n] || st.style(e, n)
            } else c = void 0;
        if (st.isEmptyObject(d)) "inline" === ("none" === c ? D(e.nodeName) : c) && (p.display = c);
        else {
            m ? "hidden" in m && (f = m.hidden) : m = st._data(e, "fxshow", {}), r && (m.hidden = !f), f ? st(e).show() : h.done(function () {
                st(e).hide()
            }), h.done(function () {
                var t;
                st._removeData(e, "fxshow");
                for (t in d) st.style(e, t, d[t])
            });
            for (n in d) a = F(f ? m[n] : 0, n, h), n in m || (m[n] = a.start, f && (a.end = a.start, a.start = "width" === n || "height" === n ? 1 : 0))
        }
    }

    function P(e, t) {
        var i, n, s, r, a;
        for (i in e)
            if (n = st.camelCase(i), s = t[n], r = e[i], st.isArray(r) && (s = r[1], r = e[i] = r[0]), i !== n && (e[n] = r, delete e[i]), a = st.cssHooks[n], a && "expand" in a) {
                r = a.expand(r), delete e[n];
                for (i in r) i in e || (e[i] = r[i], t[i] = s)
            } else t[n] = s
    }

    function H(e, t, i) {
        var n, s, r = 0,
            a = yi.length,
            o = st.Deferred().always(function () {
                delete l.elem
            }),
            l = function () {
                if (s) return !1;
                for (var t = pi || j(), i = Math.max(0, c.startTime + c.duration - t), n = i / c.duration || 0, r = 1 - n, a = 0, l = c.tweens.length; l > a; a++) c.tweens[a].run(r);
                return o.notifyWith(e, [c, r, i]), 1 > r && l ? i : (o.resolveWith(e, [c]), !1)
            },
            c = o.promise({
                "elem": e,
                "props": st.extend({}, t),
                "opts": st.extend(!0, {
                    "specialEasing": {}
                }, i),
                "originalProperties": t,
                "originalOptions": i,
                "startTime": pi || j(),
                "duration": i.duration,
                "tweens": [],
                "createTween": function (t, i) {
                    var n = st.Tween(e, c.opts, t, i, c.opts.specialEasing[t] || c.opts.easing);
                    return c.tweens.push(n), n
                },
                "stop": function (t) {
                    var i = 0,
                        n = t ? c.tweens.length : 0;
                    if (s) return this;
                    for (s = !0; n > i; i++) c.tweens[i].run(1);
                    return t ? o.resolveWith(e, [c, t]) : o.rejectWith(e, [c, t]), this
                }
            }),
            u = c.props;
        for (P(u, c.opts.specialEasing); a > r; r++)
            if (n = yi[r].call(c, e, u, c.opts)) return n;
        return st.map(u, F, c), st.isFunction(c.opts.start) && c.opts.start.call(e, c), st.fx.timer(st.extend(l, {
            "elem": e,
            "anim": c,
            "queue": c.opts.queue
        })), c.progress(c.opts.progress).done(c.opts.done, c.opts.complete).fail(c.opts.fail).always(c.opts.always)
    }

    function R(e) {
        return function (t, i) {
            "string" != typeof t && (i = t, t = "*");
            var n, s = 0,
                r = t.toLowerCase().match(bt) || [];
            if (st.isFunction(i))
                for (; n = r[s++];) "+" === n.charAt(0) ? (n = n.slice(1) || "*", (e[n] = e[n] || []).unshift(i)) : (e[n] = e[n] || []).push(i)
        }
    }

    function W(e, t, i, n) {
        function s(o) {
            var l;
            return r[o] = !0, st.each(e[o] || [], function (e, o) {
                var c = o(t, i, n);
                return "string" != typeof c || a || r[c] ? a ? !(l = c) : void 0 : (t.dataTypes.unshift(c), s(c), !1)
            }), l
        }
        var r = {},
            a = e === Bi;
        return s(t.dataTypes[0]) || !r["*"] && s("*")
    }

    function q(e, t) {
        var i, n, s = st.ajaxSettings.flatOptions || {};
        for (n in t) void 0 !== t[n] && ((s[n] ? e : i || (i = {}))[n] = t[n]);
        return i && st.extend(!0, e, i), e
    }

    function z(e, t, i) {
        for (var n, s, r, a, o = e.contents, l = e.dataTypes;
            "*" === l[0];) l.shift(), void 0 === s && (s = e.mimeType || t.getResponseHeader("Content-Type"));
        if (s)
            for (a in o)
                if (o[a] && o[a].test(s)) {
                    l.unshift(a);
                    break
                }
        if (l[0] in i) r = l[0];
        else {
            for (a in i) {
                if (!l[0] || e.converters[a + " " + l[0]]) {
                    r = a;
                    break
                }
                n || (n = a)
            }
            r = r || n
        }
        return r ? (r !== l[0] && l.unshift(r), i[r]) : void 0
    }

    function B(e, t, i, n) {
        var s, r, a, o, l, c = {},
            u = e.dataTypes.slice();
        if (u[1])
            for (a in e.converters) c[a.toLowerCase()] = e.converters[a];
        for (r = u.shift(); r;)
            if (e.responseFields[r] && (i[e.responseFields[r]] = t), !l && n && e.dataFilter && (t = e.dataFilter(t, e.dataType)), l = r, r = u.shift())
                if ("*" === r) r = l;
                else if ("*" !== l && l !== r) {
            if (a = c[l + " " + r] || c["* " + r], !a)
                for (s in c)
                    if (o = s.split(" "), o[1] === r && (a = c[l + " " + o[0]] || c["* " + o[0]])) {
                        a === !0 ? a = c[s] : c[s] !== !0 && (r = o[0], u.unshift(o[1]));
                        break
                    }
            if (a !== !0)
                if (a && e["throws"]) t = a(t);
                else try {
                    t = a(t)
                } catch (h) {
                    return {
                        "state": "parsererror",
                        "error": a ? h : "No conversion from " + l + " to " + r
                    }
                }
        }
        return {
            "state": "success",
            "data": t
        }
    }

    function Y(e, t, i, n) {
        var s;
        if (st.isArray(t)) st.each(t, function (t, s) {
            i || $i.test(e) ? n(e, s) : Y(e + "[" + ("object" == typeof s ? t : "") + "]", s, i, n)
        });
        else if (i || "object" !== st.type(t)) n(e, t);
        else
            for (s in t) Y(e + "[" + s + "]", t[s], i, n)
    }

    function U() {
        try {
            return new e.XMLHttpRequest
        } catch (t) {}
    }

    function K() {
        try {
            return new e.ActiveXObject("Microsoft.XMLHTTP")
        } catch (t) {}
    }

    function $(e) {
        return st.isWindow(e) ? e : 9 === e.nodeType ? e.defaultView || e.parentWindow : !1
    }
    var V = [],
        X = V.slice,
        G = V.concat,
        J = V.push,
        Q = V.indexOf,
        Z = {},
        et = Z.toString,
        tt = Z.hasOwnProperty,
        it = {},
        nt = "1.11.1",
        st = function (e, t) {
            return new st.fn.init(e, t)
        },
        rt = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,
        at = /^-ms-/,
        ot = /-([\da-z])/gi,
        lt = function (e, t) {
            return t.toUpperCase()
        };
    st.fn = st.prototype = {
        "jquery": nt,
        "constructor": st,
        "selector": "",
        "length": 0,
        "toArray": function () {
            return X.call(this)
        },
        "get": function (e) {
            return null != e ? 0 > e ? this[e + this.length] : this[e] : X.call(this)
        },
        "pushStack": function (e) {
            var t = st.merge(this.constructor(), e);
            return t.prevObject = this, t.context = this.context, t
        },
        "each": function (e, t) {
            return st.each(this, e, t)
        },
        "map": function (e) {
            return this.pushStack(st.map(this, function (t, i) {
                return e.call(t, i, t)
            }))
        },
        "slice": function () {
            return this.pushStack(X.apply(this, arguments))
        },
        "first": function () {
            return this.eq(0)
        },
        "last": function () {
            return this.eq(-1)
        },
        "eq": function (e) {
            var t = this.length,
                i = +e + (0 > e ? t : 0);
            return this.pushStack(i >= 0 && t > i ? [this[i]] : [])
        },
        "end": function () {
            return this.prevObject || this.constructor(null)
        },
        "push": J,
        "sort": V.sort,
        "splice": V.splice
    }, st.extend = st.fn.extend = function () {
        var e, t, i, n, s, r, a = arguments[0] || {},
            o = 1,
            l = arguments.length,
            c = !1;
        for ("boolean" == typeof a && (c = a, a = arguments[o] || {}, o++), "object" == typeof a || st.isFunction(a) || (a = {}), o === l && (a = this, o--); l > o; o++)
            if (null != (s = arguments[o]))
                for (n in s) e = a[n], i = s[n], a !== i && (c && i && (st.isPlainObject(i) || (t = st.isArray(i))) ? (t ? (t = !1, r = e && st.isArray(e) ? e : []) : r = e && st.isPlainObject(e) ? e : {}, a[n] = st.extend(c, r, i)) : void 0 !== i && (a[n] = i));
        return a
    }, st.extend({
        "expando": "jQuery" + (nt + Math.random()).replace(/\D/g, ""),
        "isReady": !0,
        "error": function (e) {
            throw new Error(e)
        },
        "noop": function () {},
        "isFunction": function (e) {
            return "function" === st.type(e)
        },
        "isArray": Array.isArray || function (e) {
            return "array" === st.type(e)
        },
        "isWindow": function (e) {
            return null != e && e == e.window
        },
        "isNumeric": function (e) {
            return !st.isArray(e) && e - parseFloat(e) >= 0
        },
        "isEmptyObject": function (e) {
            var t;
            for (t in e) return !1;
            return !0
        },
        "isPlainObject": function (e) {
            var t;
            if (!e || "object" !== st.type(e) || e.nodeType || st.isWindow(e)) return !1;
            try {
                if (e.constructor && !tt.call(e, "constructor") && !tt.call(e.constructor.prototype, "isPrototypeOf")) return !1
            } catch (i) {
                return !1
            }
            if (it.ownLast)
                for (t in e) return tt.call(e, t);
            for (t in e);
            return void 0 === t || tt.call(e, t)
        },
        "type": function (e) {
            return null == e ? e + "" : "object" == typeof e || "function" == typeof e ? Z[et.call(e)] || "object" : typeof e
        },
        "globalEval": function (t) {
            t && st.trim(t) && (e.execScript || function (t) {
                e.eval.call(e, t)
            })(t)
        },
        "camelCase": function (e) {
            return e.replace(at, "ms-").replace(ot, lt)
        },
        "nodeName": function (e, t) {
            return e.nodeName && e.nodeName.toLowerCase() === t.toLowerCase()
        },
        "each": function (e, t, n) {
            var s, r = 0,
                a = e.length,
                o = i(e);
            if (n) {
                if (o)
                    for (; a > r && (s = t.apply(e[r], n), s !== !1); r++);
                else
                    for (r in e)
                        if (s = t.apply(e[r], n), s === !1) break
            } else if (o)
                for (; a > r && (s = t.call(e[r], r, e[r]), s !== !1); r++);
            else
                for (r in e)
                    if (s = t.call(e[r], r, e[r]), s === !1) break;
            return e
        },
        "trim": function (e) {
            return null == e ? "" : (e + "").replace(rt, "")
        },
        "makeArray": function (e, t) {
            var n = t || [];
            return null != e && (i(Object(e)) ? st.merge(n, "string" == typeof e ? [e] : e) : J.call(n, e)), n
        },
        "inArray": function (e, t, i) {
            var n;
            if (t) {
                if (Q) return Q.call(t, e, i);
                for (n = t.length, i = i ? 0 > i ? Math.max(0, n + i) : i : 0; n > i; i++)
                    if (i in t && t[i] === e) return i
            }
            return -1
        },
        "merge": function (e, t) {
            for (var i = +t.length, n = 0, s = e.length; i > n;) e[s++] = t[n++];
            if (i !== i)
                for (; void 0 !== t[n];) e[s++] = t[n++];
            return e.length = s, e
        },
        "grep": function (e, t, i) {
            for (var n, s = [], r = 0, a = e.length, o = !i; a > r; r++) n = !t(e[r], r), n !== o && s.push(e[r]);
            return s
        },
        "map": function (e, t, n) {
            var s, r = 0,
                a = e.length,
                o = i(e),
                l = [];
            if (o)
                for (; a > r; r++) s = t(e[r], r, n), null != s && l.push(s);
            else
                for (r in e) s = t(e[r], r, n), null != s && l.push(s);
            return G.apply([], l)
        },
        "guid": 1,
        "proxy": function (e, t) {
            var i, n, s;
            return "string" == typeof t && (s = e[t], t = e, e = s), st.isFunction(e) ? (i = X.call(arguments, 2), n = function () {
                return e.apply(t || this, i.concat(X.call(arguments)))
            }, n.guid = e.guid = e.guid || st.guid++, n) : void 0
        },
        "now": function () {
            return +new Date
        },
        "support": it
    }), st.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function (e, t) {
        Z["[object " + t + "]"] = t.toLowerCase()
    });
    var ct =
        /*!
         * Sizzle CSS Selector Engine v1.10.19
         * http://sizzlejs.com/
         *
         * Copyright 2013 jQuery Foundation, Inc. and other contributors
         * Released under the MIT license
         * http://jquery.org/license
         *
         * Date: 2014-04-18
         */
        function (e) {
            function t(e, t, i, n) {
                var s, r, a, o, l, c, h, p, f, m;
                if ((t ? t.ownerDocument || t : W) !== I && A(t), t = t || I, i = i || [], !e || "string" != typeof e) return i;
                if (1 !== (o = t.nodeType) && 9 !== o) return [];
                if (O && !n) {
                    if (s = yt.exec(e))
                        if (a = s[1]) {
                            if (9 === o) {
                                if (r = t.getElementById(a), !r || !r.parentNode) return i;
                                if (r.id === a) return i.push(r), i
                            } else if (t.ownerDocument && (r = t.ownerDocument.getElementById(a)) && H(t, r) && r.id === a) return i.push(r), i
                        } else {
                            if (s[2]) return Z.apply(i, t.getElementsByTagName(e)), i;
                            if ((a = s[3]) && x.getElementsByClassName && t.getElementsByClassName) return Z.apply(i, t.getElementsByClassName(a)), i
                        }
                    if (x.qsa && (!F || !F.test(e))) {
                        if (p = h = R, f = t, m = 9 === o && e, 1 === o && "object" !== t.nodeName.toLowerCase()) {
                            for (c = D(e), (h = t.getAttribute("id")) ? p = h.replace(wt, "\\$&") : t.setAttribute("id", p), p = "[id='" + p + "'] ", l = c.length; l--;) c[l] = p + d(c[l]);
                            f = bt.test(e) && u(t.parentNode) || t, m = c.join(",")
                        }
                        if (m) try {
                            return Z.apply(i, f.querySelectorAll(m)), i
                        } catch (g) {} finally {
                            h || t.removeAttribute("id")
                        }
                    }
                }
                return T(e.replace(lt, "$1"), t, i, n)
            }

            function i() {
                function e(i, n) {
                    return t.push(i + " ") > k.cacheLength && delete e[t.shift()], e[i + " "] = n
                }
                var t = [];
                return e
            }

            function n(e) {
                return e[R] = !0, e
            }

            function s(e) {
                var t = I.createElement("div");
                try {
                    return !!e(t)
                } catch (i) {
                    return !1
                } finally {
                    t.parentNode && t.parentNode.removeChild(t), t = null
                }
            }

            function r(e, t) {
                for (var i = e.split("|"), n = e.length; n--;) k.attrHandle[i[n]] = t
            }

            function a(e, t) {
                var i = t && e,
                    n = i && 1 === e.nodeType && 1 === t.nodeType && (~t.sourceIndex || V) - (~e.sourceIndex || V);
                if (n) return n;
                if (i)
                    for (; i = i.nextSibling;)
                        if (i === t) return -1;
                return e ? 1 : -1
            }

            function o(e) {
                return function (t) {
                    var i = t.nodeName.toLowerCase();
                    return "input" === i && t.type === e
                }
            }

            function l(e) {
                return function (t) {
                    var i = t.nodeName.toLowerCase();
                    return ("input" === i || "button" === i) && t.type === e
                }
            }

            function c(e) {
                return n(function (t) {
                    return t = +t, n(function (i, n) {
                        for (var s, r = e([], i.length, t), a = r.length; a--;) i[s = r[a]] && (i[s] = !(n[s] = i[s]))
                    })
                })
            }

            function u(e) {
                return e && typeof e.getElementsByTagName !== $ && e
            }

            function h() {}

            function d(e) {
                for (var t = 0, i = e.length, n = ""; i > t; t++) n += e[t].value;
                return n
            }

            function p(e, t, i) {
                var n = t.dir,
                    s = i && "parentNode" === n,
                    r = z++;
                return t.first ? function (t, i, r) {
                    for (; t = t[n];)
                        if (1 === t.nodeType || s) return e(t, i, r)
                } : function (t, i, a) {
                    var o, l, c = [q, r];
                    if (a) {
                        for (; t = t[n];)
                            if ((1 === t.nodeType || s) && e(t, i, a)) return !0
                    } else
                        for (; t = t[n];)
                            if (1 === t.nodeType || s) {
                                if (l = t[R] || (t[R] = {}), (o = l[n]) && o[0] === q && o[1] === r) return c[2] = o[2];
                                if (l[n] = c, c[2] = e(t, i, a)) return !0
                            }
                }
            }

            function f(e) {
                return e.length > 1 ? function (t, i, n) {
                    for (var s = e.length; s--;)
                        if (!e[s](t, i, n)) return !1;
                    return !0
                } : e[0]
            }

            function m(e, i, n) {
                for (var s = 0, r = i.length; r > s; s++) t(e, i[s], n);
                return n
            }

            function g(e, t, i, n, s) {
                for (var r, a = [], o = 0, l = e.length, c = null != t; l > o; o++)(r = e[o]) && (!i || i(r, n, s)) && (a.push(r), c && t.push(o));
                return a
            }

            function v(e, t, i, s, r, a) {
                return s && !s[R] && (s = v(s)), r && !r[R] && (r = v(r, a)), n(function (n, a, o, l) {
                    var c, u, h, d = [],
                        p = [],
                        f = a.length,
                        v = n || m(t || "*", o.nodeType ? [o] : o, []),
                        y = !e || !n && t ? v : g(v, d, e, o, l),
                        b = i ? r || (n ? e : f || s) ? [] : a : y;
                    if (i && i(y, b, o, l), s)
                        for (c = g(b, p), s(c, [], o, l), u = c.length; u--;)(h = c[u]) && (b[p[u]] = !(y[p[u]] = h));
                    if (n) {
                        if (r || e) {
                            if (r) {
                                for (c = [], u = b.length; u--;)(h = b[u]) && c.push(y[u] = h);
                                r(null, b = [], c, l)
                            }
                            for (u = b.length; u--;)(h = b[u]) && (c = r ? tt.call(n, h) : d[u]) > -1 && (n[c] = !(a[c] = h))
                        }
                    } else b = g(b === a ? b.splice(f, b.length) : b), r ? r(null, a, b, l) : Z.apply(a, b)
                })
            }

            function y(e) {
                for (var t, i, n, s = e.length, r = k.relative[e[0].type], a = r || k.relative[" "], o = r ? 1 : 0, l = p(function (e) {
                        return e === t
                    }, a, !0), c = p(function (e) {
                        return tt.call(t, e) > -1
                    }, a, !0), u = [function (e, i, n) {
                        return !r && (n || i !== E) || ((t = i).nodeType ? l(e, i, n) : c(e, i, n))
                    }]; s > o; o++)
                    if (i = k.relative[e[o].type]) u = [p(f(u), i)];
                    else {
                        if (i = k.filter[e[o].type].apply(null, e[o].matches), i[R]) {
                            for (n = ++o; s > n && !k.relative[e[n].type]; n++);
                            return v(o > 1 && f(u), o > 1 && d(e.slice(0, o - 1).concat({
                                "value": " " === e[o - 2].type ? "*" : ""
                            })).replace(lt, "$1"), i, n > o && y(e.slice(o, n)), s > n && y(e = e.slice(n)), s > n && d(e))
                        }
                        u.push(i)
                    }
                return f(u)
            }

            function b(e, i) {
                var s = i.length > 0,
                    r = e.length > 0,
                    a = function (n, a, o, l, c) {
                        var u, h, d, p = 0,
                            f = "0",
                            m = n && [],
                            v = [],
                            y = E,
                            b = n || r && k.find.TAG("*", c),
                            w = q += null == y ? 1 : Math.random() || .1,
                            x = b.length;
                        for (c && (E = a !== I && a); f !== x && null != (u = b[f]); f++) {
                            if (r && u) {
                                for (h = 0; d = e[h++];)
                                    if (d(u, a, o)) {
                                        l.push(u);
                                        break
                                    }
                                c && (q = w)
                            }
                            s && ((u = !d && u) && p--, n && m.push(u))
                        }
                        if (p += f, s && f !== p) {
                            for (h = 0; d = i[h++];) d(m, v, a, o);
                            if (n) {
                                if (p > 0)
                                    for (; f--;) m[f] || v[f] || (v[f] = J.call(l));
                                v = g(v)
                            }
                            Z.apply(l, v), c && !n && v.length > 0 && p + i.length > 1 && t.uniqueSort(l)
                        }
                        return c && (q = w, E = y), m
                    };
                return s ? n(a) : a
            }
            var w, x, k, _, C, D, S, T, E, M, N, A, I, j, O, F, L, P, H, R = "sizzle" + -new Date,
                W = e.document,
                q = 0,
                z = 0,
                B = i(),
                Y = i(),
                U = i(),
                K = function (e, t) {
                    return e === t && (N = !0), 0
                },
                $ = "undefined",
                V = 1 << 31,
                X = {}.hasOwnProperty,
                G = [],
                J = G.pop,
                Q = G.push,
                Z = G.push,
                et = G.slice,
                tt = G.indexOf || function (e) {
                    for (var t = 0, i = this.length; i > t; t++)
                        if (this[t] === e) return t;
                    return -1
                },
                it = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
                nt = "[\\x20\\t\\r\\n\\f]",
                st = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",
                rt = st.replace("w", "w#"),
                at = "\\[" + nt + "*(" + st + ")(?:" + nt + "*([*^$|!~]?=)" + nt + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + rt + "))|)" + nt + "*\\]",
                ot = ":(" + st + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + at + ")*)|.*)\\)|)",
                lt = new RegExp("^" + nt + "+|((?:^|[^\\\\])(?:\\\\.)*)" + nt + "+$", "g"),
                ct = new RegExp("^" + nt + "*," + nt + "*"),
                ut = new RegExp("^" + nt + "*([>+~]|" + nt + ")" + nt + "*"),
                ht = new RegExp("=" + nt + "*([^\\]'\"]*?)" + nt + "*\\]", "g"),
                dt = new RegExp(ot),
                pt = new RegExp("^" + rt + "$"),
                ft = {
                    "ID": new RegExp("^#(" + st + ")"),
                    "CLASS": new RegExp("^\\.(" + st + ")"),
                    "TAG": new RegExp("^(" + st.replace("w", "w*") + ")"),
                    "ATTR": new RegExp("^" + at),
                    "PSEUDO": new RegExp("^" + ot),
                    "CHILD": new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + nt + "*(even|odd|(([+-]|)(\\d*)n|)" + nt + "*(?:([+-]|)" + nt + "*(\\d+)|))" + nt + "*\\)|)", "i"),
                    "bool": new RegExp("^(?:" + it + ")$", "i"),
                    "needsContext": new RegExp("^" + nt + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + nt + "*((?:-\\d)?\\d*)" + nt + "*\\)|)(?=[^-]|$)", "i")
                },
                mt = /^(?:input|select|textarea|button)$/i,
                gt = /^h\d$/i,
                vt = /^[^{]+\{\s*\[native \w/,
                yt = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
                bt = /[+~]/,
                wt = /'|\\/g,
                xt = new RegExp("\\\\([\\da-f]{1,6}" + nt + "?|(" + nt + ")|.)", "ig"),
                kt = function (e, t, i) {
                    var n = "0x" + t - 65536;
                    return n !== n || i ? t : 0 > n ? String.fromCharCode(n + 65536) : String.fromCharCode(n >> 10 | 55296, 1023 & n | 56320)
                };
            try {
                Z.apply(G = et.call(W.childNodes), W.childNodes), G[W.childNodes.length].nodeType
            } catch (_t) {
                Z = {
                    "apply": G.length ? function (e, t) {
                        Q.apply(e, et.call(t))
                    } : function (e, t) {
                        for (var i = e.length, n = 0; e[i++] = t[n++];);
                        e.length = i - 1
                    }
                }
            }
            x = t.support = {}, C = t.isXML = function (e) {
                var t = e && (e.ownerDocument || e).documentElement;
                return t ? "HTML" !== t.nodeName : !1
            }, A = t.setDocument = function (e) {
                var t, i = e ? e.ownerDocument || e : W,
                    n = i.defaultView;
                return i !== I && 9 === i.nodeType && i.documentElement ? (I = i, j = i.documentElement, O = !C(i), n && n !== n.top && (n.addEventListener ? n.addEventListener("unload", function () {
                    A()
                }, !1) : n.attachEvent && n.attachEvent("onunload", function () {
                    A()
                })), x.attributes = s(function (e) {
                    return e.className = "i", !e.getAttribute("className")
                }), x.getElementsByTagName = s(function (e) {
                    return e.appendChild(i.createComment("")), !e.getElementsByTagName("*").length
                }), x.getElementsByClassName = vt.test(i.getElementsByClassName) && s(function (e) {
                    return e.innerHTML = "<div class='a'></div><div class='a i'></div>", e.firstChild.className = "i", 2 === e.getElementsByClassName("i").length
                }), x.getById = s(function (e) {
                    return j.appendChild(e).id = R, !i.getElementsByName || !i.getElementsByName(R).length
                }), x.getById ? (k.find.ID = function (e, t) {
                    if (typeof t.getElementById !== $ && O) {
                        var i = t.getElementById(e);
                        return i && i.parentNode ? [i] : []
                    }
                }, k.filter.ID = function (e) {
                    var t = e.replace(xt, kt);
                    return function (e) {
                        return e.getAttribute("id") === t
                    }
                }) : (delete k.find.ID, k.filter.ID = function (e) {
                    var t = e.replace(xt, kt);
                    return function (e) {
                        var i = typeof e.getAttributeNode !== $ && e.getAttributeNode("id");
                        return i && i.value === t
                    }
                }), k.find.TAG = x.getElementsByTagName ? function (e, t) {
                    return typeof t.getElementsByTagName !== $ ? t.getElementsByTagName(e) : void 0
                } : function (e, t) {
                    var i, n = [],
                        s = 0,
                        r = t.getElementsByTagName(e);
                    if ("*" === e) {
                        for (; i = r[s++];) 1 === i.nodeType && n.push(i);
                        return n
                    }
                    return r
                }, k.find.CLASS = x.getElementsByClassName && function (e, t) {
                    return typeof t.getElementsByClassName !== $ && O ? t.getElementsByClassName(e) : void 0
                }, L = [], F = [], (x.qsa = vt.test(i.querySelectorAll)) && (s(function (e) {
                    e.innerHTML = "<select msallowclip=''><option selected=''></option></select>", e.querySelectorAll("[msallowclip^='']").length && F.push("[*^$]=" + nt + "*(?:''|\"\")"), e.querySelectorAll("[selected]").length || F.push("\\[" + nt + "*(?:value|" + it + ")"), e.querySelectorAll(":checked").length || F.push(":checked")
                }), s(function (e) {
                    var t = i.createElement("input");
                    t.setAttribute("type", "hidden"), e.appendChild(t).setAttribute("name", "D"), e.querySelectorAll("[name=d]").length && F.push("name" + nt + "*[*^$|!~]?="), e.querySelectorAll(":enabled").length || F.push(":enabled", ":disabled"), e.querySelectorAll("*,:x"), F.push(",.*:")
                })), (x.matchesSelector = vt.test(P = j.matches || j.webkitMatchesSelector || j.mozMatchesSelector || j.oMatchesSelector || j.msMatchesSelector)) && s(function (e) {
                    x.disconnectedMatch = P.call(e, "div"), P.call(e, "[s!='']:x"), L.push("!=", ot)
                }), F = F.length && new RegExp(F.join("|")), L = L.length && new RegExp(L.join("|")), t = vt.test(j.compareDocumentPosition), H = t || vt.test(j.contains) ? function (e, t) {
                    var i = 9 === e.nodeType ? e.documentElement : e,
                        n = t && t.parentNode;
                    return e === n || !(!n || 1 !== n.nodeType || !(i.contains ? i.contains(n) : e.compareDocumentPosition && 16 & e.compareDocumentPosition(n)))
                } : function (e, t) {
                    if (t)
                        for (; t = t.parentNode;)
                            if (t === e) return !0;
                    return !1
                }, K = t ? function (e, t) {
                    if (e === t) return N = !0, 0;
                    var n = !e.compareDocumentPosition - !t.compareDocumentPosition;
                    return n ? n : (n = (e.ownerDocument || e) === (t.ownerDocument || t) ? e.compareDocumentPosition(t) : 1, 1 & n || !x.sortDetached && t.compareDocumentPosition(e) === n ? e === i || e.ownerDocument === W && H(W, e) ? -1 : t === i || t.ownerDocument === W && H(W, t) ? 1 : M ? tt.call(M, e) - tt.call(M, t) : 0 : 4 & n ? -1 : 1)
                } : function (e, t) {
                    if (e === t) return N = !0, 0;
                    var n, s = 0,
                        r = e.parentNode,
                        o = t.parentNode,
                        l = [e],
                        c = [t];
                    if (!r || !o) return e === i ? -1 : t === i ? 1 : r ? -1 : o ? 1 : M ? tt.call(M, e) - tt.call(M, t) : 0;
                    if (r === o) return a(e, t);
                    for (n = e; n = n.parentNode;) l.unshift(n);
                    for (n = t; n = n.parentNode;) c.unshift(n);
                    for (; l[s] === c[s];) s++;
                    return s ? a(l[s], c[s]) : l[s] === W ? -1 : c[s] === W ? 1 : 0
                }, i) : I
            }, t.matches = function (e, i) {
                return t(e, null, null, i)
            }, t.matchesSelector = function (e, i) {
                if ((e.ownerDocument || e) !== I && A(e), i = i.replace(ht, "='$1']"), !(!x.matchesSelector || !O || L && L.test(i) || F && F.test(i))) try {
                    var n = P.call(e, i);
                    if (n || x.disconnectedMatch || e.document && 11 !== e.document.nodeType) return n
                } catch (s) {}
                return t(i, I, null, [e]).length > 0
            }, t.contains = function (e, t) {
                return (e.ownerDocument || e) !== I && A(e), H(e, t)
            }, t.attr = function (e, t) {
                (e.ownerDocument || e) !== I && A(e);
                var i = k.attrHandle[t.toLowerCase()],
                    n = i && X.call(k.attrHandle, t.toLowerCase()) ? i(e, t, !O) : void 0;
                return void 0 !== n ? n : x.attributes || !O ? e.getAttribute(t) : (n = e.getAttributeNode(t)) && n.specified ? n.value : null
            }, t.error = function (e) {
                throw new Error("Syntax error, unrecognized expression: " + e)
            }, t.uniqueSort = function (e) {
                var t, i = [],
                    n = 0,
                    s = 0;
                if (N = !x.detectDuplicates, M = !x.sortStable && e.slice(0), e.sort(K), N) {
                    for (; t = e[s++];) t === e[s] && (n = i.push(s));
                    for (; n--;) e.splice(i[n], 1)
                }
                return M = null, e
            }, _ = t.getText = function (e) {
                var t, i = "",
                    n = 0,
                    s = e.nodeType;
                if (s) {
                    if (1 === s || 9 === s || 11 === s) {
                        if ("string" == typeof e.textContent) return e.textContent;
                        for (e = e.firstChild; e; e = e.nextSibling) i += _(e)
                    } else if (3 === s || 4 === s) return e.nodeValue
                } else
                    for (; t = e[n++];) i += _(t);
                return i
            }, k = t.selectors = {
                "cacheLength": 50,
                "createPseudo": n,
                "match": ft,
                "attrHandle": {},
                "find": {},
                "relative": {
                    ">": {
                        "dir": "parentNode",
                        "first": !0
                    },
                    " ": {
                        "dir": "parentNode"
                    },
                    "+": {
                        "dir": "previousSibling",
                        "first": !0
                    },
                    "~": {
                        "dir": "previousSibling"
                    }
                },
                "preFilter": {
                    "ATTR": function (e) {
                        return e[1] = e[1].replace(xt, kt), e[3] = (e[3] || e[4] || e[5] || "").replace(xt, kt), "~=" === e[2] && (e[3] = " " + e[3] + " "), e.slice(0, 4)
                    },
                    "CHILD": function (e) {
                        return e[1] = e[1].toLowerCase(), "nth" === e[1].slice(0, 3) ? (e[3] || t.error(e[0]), e[4] = +(e[4] ? e[5] + (e[6] || 1) : 2 * ("even" === e[3] || "odd" === e[3])), e[5] = +(e[7] + e[8] || "odd" === e[3])) : e[3] && t.error(e[0]), e
                    },
                    "PSEUDO": function (e) {
                        var t, i = !e[6] && e[2];
                        return ft.CHILD.test(e[0]) ? null : (e[3] ? e[2] = e[4] || e[5] || "" : i && dt.test(i) && (t = D(i, !0)) && (t = i.indexOf(")", i.length - t) - i.length) && (e[0] = e[0].slice(0, t), e[2] = i.slice(0, t)), e.slice(0, 3))
                    }
                },
                "filter": {
                    "TAG": function (e) {
                        var t = e.replace(xt, kt).toLowerCase();
                        return "*" === e ? function () {
                            return !0
                        } : function (e) {
                            return e.nodeName && e.nodeName.toLowerCase() === t
                        }
                    },
                    "CLASS": function (e) {
                        var t = B[e + " "];
                        return t || (t = new RegExp("(^|" + nt + ")" + e + "(" + nt + "|$)")) && B(e, function (e) {
                            return t.test("string" == typeof e.className && e.className || typeof e.getAttribute !== $ && e.getAttribute("class") || "")
                        })
                    },
                    "ATTR": function (e, i, n) {
                        return function (s) {
                            var r = t.attr(s, e);
                            return null == r ? "!=" === i : i ? (r += "", "=" === i ? r === n : "!=" === i ? r !== n : "^=" === i ? n && 0 === r.indexOf(n) : "*=" === i ? n && r.indexOf(n) > -1 : "$=" === i ? n && r.slice(-n.length) === n : "~=" === i ? (" " + r + " ").indexOf(n) > -1 : "|=" === i ? r === n || r.slice(0, n.length + 1) === n + "-" : !1) : !0
                        }
                    },
                    "CHILD": function (e, t, i, n, s) {
                        var r = "nth" !== e.slice(0, 3),
                            a = "last" !== e.slice(-4),
                            o = "of-type" === t;
                        return 1 === n && 0 === s ? function (e) {
                            return !!e.parentNode
                        } : function (t, i, l) {
                            var c, u, h, d, p, f, m = r !== a ? "nextSibling" : "previousSibling",
                                g = t.parentNode,
                                v = o && t.nodeName.toLowerCase(),
                                y = !l && !o;
                            if (g) {
                                if (r) {
                                    for (; m;) {
                                        for (h = t; h = h[m];)
                                            if (o ? h.nodeName.toLowerCase() === v : 1 === h.nodeType) return !1;
                                        f = m = "only" === e && !f && "nextSibling"
                                    }
                                    return !0
                                }
                                if (f = [a ? g.firstChild : g.lastChild], a && y) {
                                    for (u = g[R] || (g[R] = {}), c = u[e] || [], p = c[0] === q && c[1], d = c[0] === q && c[2], h = p && g.childNodes[p]; h = ++p && h && h[m] || (d = p = 0) || f.pop();)
                                        if (1 === h.nodeType && ++d && h === t) {
                                            u[e] = [q, p, d];
                                            break
                                        }
                                } else if (y && (c = (t[R] || (t[R] = {}))[e]) && c[0] === q) d = c[1];
                                else
                                    for (;
                                        (h = ++p && h && h[m] || (d = p = 0) || f.pop()) && ((o ? h.nodeName.toLowerCase() !== v : 1 !== h.nodeType) || !++d || (y && ((h[R] || (h[R] = {}))[e] = [q, d]), h !== t)););
                                return d -= s, d === n || d % n === 0 && d / n >= 0
                            }
                        }
                    },
                    "PSEUDO": function (e, i) {
                        var s, r = k.pseudos[e] || k.setFilters[e.toLowerCase()] || t.error("unsupported pseudo: " + e);
                        return r[R] ? r(i) : r.length > 1 ? (s = [e, e, "", i], k.setFilters.hasOwnProperty(e.toLowerCase()) ? n(function (e, t) {
                            for (var n, s = r(e, i), a = s.length; a--;) n = tt.call(e, s[a]), e[n] = !(t[n] = s[a])
                        }) : function (e) {
                            return r(e, 0, s)
                        }) : r
                    }
                },
                "pseudos": {
                    "not": n(function (e) {
                        var t = [],
                            i = [],
                            s = S(e.replace(lt, "$1"));
                        return s[R] ? n(function (e, t, i, n) {
                            for (var r, a = s(e, null, n, []), o = e.length; o--;)(r = a[o]) && (e[o] = !(t[o] = r))
                        }) : function (e, n, r) {
                            return t[0] = e, s(t, null, r, i), !i.pop()
                        }
                    }),
                    "has": n(function (e) {
                        return function (i) {
                            return t(e, i).length > 0
                        }
                    }),
                    "contains": n(function (e) {
                        return function (t) {
                            return (t.textContent || t.innerText || _(t)).indexOf(e) > -1
                        }
                    }),
                    "lang": n(function (e) {
                        return pt.test(e || "") || t.error("unsupported lang: " + e), e = e.replace(xt, kt).toLowerCase(),
                            function (t) {
                                var i;
                                do
                                    if (i = O ? t.lang : t.getAttribute("xml:lang") || t.getAttribute("lang")) return i = i.toLowerCase(), i === e || 0 === i.indexOf(e + "-"); while ((t = t.parentNode) && 1 === t.nodeType);
                                return !1
                            }
                    }),
                    "target": function (t) {
                        var i = e.location && e.location.hash;
                        return i && i.slice(1) === t.id
                    },
                    "root": function (e) {
                        return e === j
                    },
                    "focus": function (e) {
                        return e === I.activeElement && (!I.hasFocus || I.hasFocus()) && !!(e.type || e.href || ~e.tabIndex)
                    },
                    "enabled": function (e) {
                        return e.disabled === !1
                    },
                    "disabled": function (e) {
                        return e.disabled === !0
                    },
                    "checked": function (e) {
                        var t = e.nodeName.toLowerCase();
                        return "input" === t && !!e.checked || "option" === t && !!e.selected
                    },
                    "selected": function (e) {
                        return e.parentNode && e.parentNode.selectedIndex, e.selected === !0
                    },
                    "empty": function (e) {
                        for (e = e.firstChild; e; e = e.nextSibling)
                            if (e.nodeType < 6) return !1;
                        return !0
                    },
                    "parent": function (e) {
                        return !k.pseudos.empty(e)
                    },
                    "header": function (e) {
                        return gt.test(e.nodeName)
                    },
                    "input": function (e) {
                        return mt.test(e.nodeName)
                    },
                    "button": function (e) {
                        var t = e.nodeName.toLowerCase();
                        return "input" === t && "button" === e.type || "button" === t
                    },
                    "text": function (e) {
                        var t;
                        return "input" === e.nodeName.toLowerCase() && "text" === e.type && (null == (t = e.getAttribute("type")) || "text" === t.toLowerCase())
                    },
                    "first": c(function () {
                        return [0]
                    }),
                    "last": c(function (e, t) {
                        return [t - 1]
                    }),
                    "eq": c(function (e, t, i) {
                        return [0 > i ? i + t : i]
                    }),
                    "even": c(function (e, t) {
                        for (var i = 0; t > i; i += 2) e.push(i);
                        return e
                    }),
                    "odd": c(function (e, t) {
                        for (var i = 1; t > i; i += 2) e.push(i);
                        return e
                    }),
                    "lt": c(function (e, t, i) {
                        for (var n = 0 > i ? i + t : i; --n >= 0;) e.push(n);
                        return e
                    }),
                    "gt": c(function (e, t, i) {
                        for (var n = 0 > i ? i + t : i; ++n < t;) e.push(n);
                        return e
                    })
                }
            }, k.pseudos.nth = k.pseudos.eq;
            for (w in {
                    "radio": !0,
                    "checkbox": !0,
                    "file": !0,
                    "password": !0,
                    "image": !0
                }) k.pseudos[w] = o(w);
            for (w in {
                    "submit": !0,
                    "reset": !0
                }) k.pseudos[w] = l(w);
            return h.prototype = k.filters = k.pseudos, k.setFilters = new h, D = t.tokenize = function (e, i) {
                var n, s, r, a, o, l, c, u = Y[e + " "];
                if (u) return i ? 0 : u.slice(0);
                for (o = e, l = [], c = k.preFilter; o;) {
                    (!n || (s = ct.exec(o))) && (s && (o = o.slice(s[0].length) || o), l.push(r = [])), n = !1, (s = ut.exec(o)) && (n = s.shift(), r.push({
                        "value": n,
                        "type": s[0].replace(lt, " ")
                    }), o = o.slice(n.length));
                    for (a in k.filter) !(s = ft[a].exec(o)) || c[a] && !(s = c[a](s)) || (n = s.shift(), r.push({
                        "value": n,
                        "type": a,
                        "matches": s
                    }), o = o.slice(n.length));
                    if (!n) break
                }
                return i ? o.length : o ? t.error(e) : Y(e, l).slice(0)
            }, S = t.compile = function (e, t) {
                var i, n = [],
                    s = [],
                    r = U[e + " "];
                if (!r) {
                    for (t || (t = D(e)), i = t.length; i--;) r = y(t[i]), r[R] ? n.push(r) : s.push(r);
                    r = U(e, b(s, n)), r.selector = e
                }
                return r
            }, T = t.select = function (e, t, i, n) {
                var s, r, a, o, l, c = "function" == typeof e && e,
                    h = !n && D(e = c.selector || e);
                if (i = i || [], 1 === h.length) {
                    if (r = h[0] = h[0].slice(0), r.length > 2 && "ID" === (a = r[0]).type && x.getById && 9 === t.nodeType && O && k.relative[r[1].type]) {
                        if (t = (k.find.ID(a.matches[0].replace(xt, kt), t) || [])[0], !t) return i;
                        c && (t = t.parentNode), e = e.slice(r.shift().value.length)
                    }
                    for (s = ft.needsContext.test(e) ? 0 : r.length; s-- && (a = r[s], !k.relative[o = a.type]);)
                        if ((l = k.find[o]) && (n = l(a.matches[0].replace(xt, kt), bt.test(r[0].type) && u(t.parentNode) || t))) {
                            if (r.splice(s, 1), e = n.length && d(r), !e) return Z.apply(i, n), i;
                            break
                        }
                }
                return (c || S(e, h))(n, t, !O, i, bt.test(e) && u(t.parentNode) || t), i
            }, x.sortStable = R.split("").sort(K).join("") === R, x.detectDuplicates = !!N, A(), x.sortDetached = s(function (e) {
                return 1 & e.compareDocumentPosition(I.createElement("div"))
            }), s(function (e) {
                return e.innerHTML = "<a href='#'></a>", "#" === e.firstChild.getAttribute("href")
            }) || r("type|href|height|width", function (e, t, i) {
                return i ? void 0 : e.getAttribute(t, "type" === t.toLowerCase() ? 1 : 2)
            }), x.attributes && s(function (e) {
                return e.innerHTML = "<input/>", e.firstChild.setAttribute("value", ""), "" === e.firstChild.getAttribute("value")
            }) || r("value", function (e, t, i) {
                return i || "input" !== e.nodeName.toLowerCase() ? void 0 : e.defaultValue
            }), s(function (e) {
                return null == e.getAttribute("disabled")
            }) || r(it, function (e, t, i) {
                var n;
                return i ? void 0 : e[t] === !0 ? t.toLowerCase() : (n = e.getAttributeNode(t)) && n.specified ? n.value : null
            }), t
        }(e);
    st.find = ct, st.expr = ct.selectors, st.expr[":"] = st.expr.pseudos, st.unique = ct.uniqueSort, st.text = ct.getText, st.isXMLDoc = ct.isXML, st.contains = ct.contains;
    var ut = st.expr.match.needsContext,
        ht = /^<(\w+)\s*\/?>(?:<\/\1>|)$/,
        dt = /^.[^:#\[\.,]*$/;
    st.filter = function (e, t, i) {
        var n = t[0];
        return i && (e = ":not(" + e + ")"), 1 === t.length && 1 === n.nodeType ? st.find.matchesSelector(n, e) ? [n] : [] : st.find.matches(e, st.grep(t, function (e) {
            return 1 === e.nodeType
        }))
    }, st.fn.extend({
        "find": function (e) {
            var t, i = [],
                n = this,
                s = n.length;
            if ("string" != typeof e) return this.pushStack(st(e).filter(function () {
                for (t = 0; s > t; t++)
                    if (st.contains(n[t], this)) return !0
            }));
            for (t = 0; s > t; t++) st.find(e, n[t], i);
            return i = this.pushStack(s > 1 ? st.unique(i) : i), i.selector = this.selector ? this.selector + " " + e : e, i
        },
        "filter": function (e) {
            return this.pushStack(n(this, e || [], !1))
        },
        "not": function (e) {
            return this.pushStack(n(this, e || [], !0))
        },
        "is": function (e) {
            return !!n(this, "string" == typeof e && ut.test(e) ? st(e) : e || [], !1).length
        }
    });
    var pt, ft = e.document,
        mt = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/,
        gt = st.fn.init = function (e, t) {
            var i, n;
            if (!e) return this;
            if ("string" == typeof e) {
                if (i = "<" === e.charAt(0) && ">" === e.charAt(e.length - 1) && e.length >= 3 ? [null, e, null] : mt.exec(e), !i || !i[1] && t) return !t || t.jquery ? (t || pt).find(e) : this.constructor(t).find(e);
                if (i[1]) {
                    if (t = t instanceof st ? t[0] : t, st.merge(this, st.parseHTML(i[1], t && t.nodeType ? t.ownerDocument || t : ft, !0)), ht.test(i[1]) && st.isPlainObject(t))
                        for (i in t) st.isFunction(this[i]) ? this[i](t[i]) : this.attr(i, t[i]);
                    return this
                }
                if (n = ft.getElementById(i[2]), n && n.parentNode) {
                    if (n.id !== i[2]) return pt.find(e);
                    this.length = 1, this[0] = n
                }
                return this.context = ft, this.selector = e, this
            }
            return e.nodeType ? (this.context = this[0] = e, this.length = 1, this) : st.isFunction(e) ? "undefined" != typeof pt.ready ? pt.ready(e) : e(st) : (void 0 !== e.selector && (this.selector = e.selector, this.context = e.context), st.makeArray(e, this))
        };
    gt.prototype = st.fn, pt = st(ft);
    var vt = /^(?:parents|prev(?:Until|All))/,
        yt = {
            "children": !0,
            "contents": !0,
            "next": !0,
            "prev": !0
        };
    st.extend({
        "dir": function (e, t, i) {
            for (var n = [], s = e[t]; s && 9 !== s.nodeType && (void 0 === i || 1 !== s.nodeType || !st(s).is(i));) 1 === s.nodeType && n.push(s), s = s[t];
            return n
        },
        "sibling": function (e, t) {
            for (var i = []; e; e = e.nextSibling) 1 === e.nodeType && e !== t && i.push(e);
            return i
        }
    }), st.fn.extend({
        "has": function (e) {
            var t, i = st(e, this),
                n = i.length;
            return this.filter(function () {
                for (t = 0; n > t; t++)
                    if (st.contains(this, i[t])) return !0
            })
        },
        "closest": function (e, t) {
            for (var i, n = 0, s = this.length, r = [], a = ut.test(e) || "string" != typeof e ? st(e, t || this.context) : 0; s > n; n++)
                for (i = this[n]; i && i !== t; i = i.parentNode)
                    if (i.nodeType < 11 && (a ? a.index(i) > -1 : 1 === i.nodeType && st.find.matchesSelector(i, e))) {
                        r.push(i);
                        break
                    }
            return this.pushStack(r.length > 1 ? st.unique(r) : r)
        },
        "index": function (e) {
            return e ? "string" == typeof e ? st.inArray(this[0], st(e)) : st.inArray(e.jquery ? e[0] : e, this) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
        },
        "add": function (e, t) {
            return this.pushStack(st.unique(st.merge(this.get(), st(e, t))))
        },
        "addBack": function (e) {
            return this.add(null == e ? this.prevObject : this.prevObject.filter(e))
        }
    }), st.each({
        "parent": function (e) {
            var t = e.parentNode;
            return t && 11 !== t.nodeType ? t : null
        },
        "parents": function (e) {
            return st.dir(e, "parentNode")
        },
        "parentsUntil": function (e, t, i) {
            return st.dir(e, "parentNode", i)
        },
        "next": function (e) {
            return s(e, "nextSibling")
        },
        "prev": function (e) {
            return s(e, "previousSibling")
        },
        "nextAll": function (e) {
            return st.dir(e, "nextSibling")
        },
        "prevAll": function (e) {
            return st.dir(e, "previousSibling")
        },
        "nextUntil": function (e, t, i) {
            return st.dir(e, "nextSibling", i)
        },
        "prevUntil": function (e, t, i) {
            return st.dir(e, "previousSibling", i)
        },
        "siblings": function (e) {
            return st.sibling((e.parentNode || {}).firstChild, e)
        },
        "children": function (e) {
            return st.sibling(e.firstChild)
        },
        "contents": function (e) {
            return st.nodeName(e, "iframe") ? e.contentDocument || e.contentWindow.document : st.merge([], e.childNodes)
        }
    }, function (e, t) {
        st.fn[e] = function (i, n) {
            var s = st.map(this, t, i);
            return "Until" !== e.slice(-5) && (n = i), n && "string" == typeof n && (s = st.filter(n, s)), this.length > 1 && (yt[e] || (s = st.unique(s)), vt.test(e) && (s = s.reverse())), this.pushStack(s)
        }
    });
    var bt = /\S+/g,
        wt = {};
    st.Callbacks = function (e) {
        e = "string" == typeof e ? wt[e] || r(e) : st.extend({}, e);
        var t, i, n, s, a, o, l = [],
            c = !e.once && [],
            u = function (r) {
                for (i = e.memory && r, n = !0, a = o || 0, o = 0, s = l.length, t = !0; l && s > a; a++)
                    if (l[a].apply(r[0], r[1]) === !1 && e.stopOnFalse) {
                        i = !1;
                        break
                    }
                t = !1, l && (c ? c.length && u(c.shift()) : i ? l = [] : h.disable())
            },
            h = {
                "add": function () {
                    if (l) {
                        var n = l.length;
                        ! function r(t) {
                            st.each(t, function (t, i) {
                                var n = st.type(i);
                                "function" === n ? e.unique && h.has(i) || l.push(i) : i && i.length && "string" !== n && r(i)
                            })
                        }(arguments), t ? s = l.length : i && (o = n, u(i))
                    }
                    return this
                },
                "remove": function () {
                    return l && st.each(arguments, function (e, i) {
                        for (var n;
                            (n = st.inArray(i, l, n)) > -1;) l.splice(n, 1), t && (s >= n && s--, a >= n && a--)
                    }), this
                },
                "has": function (e) {
                    return e ? st.inArray(e, l) > -1 : !(!l || !l.length)
                },
                "empty": function () {
                    return l = [], s = 0, this
                },
                "disable": function () {
                    return l = c = i = void 0, this
                },
                "disabled": function () {
                    return !l
                },
                "lock": function () {
                    return c = void 0, i || h.disable(), this
                },
                "locked": function () {
                    return !c
                },
                "fireWith": function (e, i) {
                    return !l || n && !c || (i = i || [], i = [e, i.slice ? i.slice() : i], t ? c.push(i) : u(i)), this
                },
                "fire": function () {
                    return h.fireWith(this, arguments), this
                },
                "fired": function () {
                    return !!n
                }
            };
        return h
    }, st.extend({
        "Deferred": function (e) {
            var t = [
                    ["resolve", "done", st.Callbacks("once memory"), "resolved"],
                    ["reject", "fail", st.Callbacks("once memory"), "rejected"],
                    ["notify", "progress", st.Callbacks("memory")]
                ],
                i = "pending",
                n = {
                    "state": function () {
                        return i
                    },
                    "always": function () {
                        return s.done(arguments).fail(arguments), this
                    },
                    "then": function () {
                        var e = arguments;
                        return st.Deferred(function (i) {
                            st.each(t, function (t, r) {
                                var a = st.isFunction(e[t]) && e[t];
                                s[r[1]](function () {
                                    var e = a && a.apply(this, arguments);
                                    e && st.isFunction(e.promise) ? e.promise().done(i.resolve).fail(i.reject).progress(i.notify) : i[r[0] + "With"](this === n ? i.promise() : this, a ? [e] : arguments)
                                })
                            }), e = null
                        }).promise()
                    },
                    "promise": function (e) {
                        return null != e ? st.extend(e, n) : n
                    }
                },
                s = {};
            return n.pipe = n.then, st.each(t, function (e, r) {
                var a = r[2],
                    o = r[3];
                n[r[1]] = a.add, o && a.add(function () {
                    i = o
                }, t[1 ^ e][2].disable, t[2][2].lock), s[r[0]] = function () {
                    return s[r[0] + "With"](this === s ? n : this, arguments), this
                }, s[r[0] + "With"] = a.fireWith
            }), n.promise(s), e && e.call(s, s), s
        },
        "when": function (e) {
            var t, i, n, s = 0,
                r = X.call(arguments),
                a = r.length,
                o = 1 !== a || e && st.isFunction(e.promise) ? a : 0,
                l = 1 === o ? e : st.Deferred(),
                c = function (e, i, n) {
                    return function (s) {
                        i[e] = this, n[e] = arguments.length > 1 ? X.call(arguments) : s, n === t ? l.notifyWith(i, n) : --o || l.resolveWith(i, n)
                    }
                };
            if (a > 1)
                for (t = new Array(a), i = new Array(a), n = new Array(a); a > s; s++) r[s] && st.isFunction(r[s].promise) ? r[s].promise().done(c(s, n, r)).fail(l.reject).progress(c(s, i, t)) : --o;
            return o || l.resolveWith(n, r), l.promise()
        }
    });
    var xt;
    st.fn.ready = function (e) {
        return st.ready.promise().done(e), this
    }, st.extend({
        "isReady": !1,
        "readyWait": 1,
        "holdReady": function (e) {
            e ? st.readyWait++ : st.ready(!0)
        },
        "ready": function (e) {
            if (e === !0 ? !--st.readyWait : !st.isReady) {
                if (!ft.body) return setTimeout(st.ready);
                st.isReady = !0, e !== !0 && --st.readyWait > 0 || (xt.resolveWith(ft, [st]), st.fn.triggerHandler && (st(ft).triggerHandler("ready"), st(ft).off("ready")))
            }
        }
    }), st.ready.promise = function (t) {
        if (!xt)
            if (xt = st.Deferred(), "complete" === ft.readyState) setTimeout(st.ready);
            else if (ft.addEventListener) ft.addEventListener("DOMContentLoaded", o, !1), e.addEventListener("load", o, !1);
        else {
            ft.attachEvent("onreadystatechange", o), e.attachEvent("onload", o);
            var i = !1;
            try {
                i = null == e.frameElement && ft.documentElement
            } catch (n) {}
            i && i.doScroll && ! function s() {
                if (!st.isReady) {
                    try {
                        i.doScroll("left")
                    } catch (e) {
                        return setTimeout(s, 50)
                    }
                    a(), st.ready()
                }
            }()
        }
        return xt.promise(t)
    };
    var kt, _t = "undefined";
    for (kt in st(it)) break;
    it.ownLast = "0" !== kt, it.inlineBlockNeedsLayout = !1, st(function () {
            var e, t, i, n;
            i = ft.getElementsByTagName("body")[0], i && i.style && (t = ft.createElement("div"), n = ft.createElement("div"), n.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", i.appendChild(n).appendChild(t), typeof t.style.zoom !== _t && (t.style.cssText = "display:inline;margin:0;border:0;padding:1px;width:1px;zoom:1", it.inlineBlockNeedsLayout = e = 3 === t.offsetWidth, e && (i.style.zoom = 1)), i.removeChild(n))
        }),
        function () {
            var e = ft.createElement("div");
            if (null == it.deleteExpando) {
                it.deleteExpando = !0;
                try {
                    delete e.test
                } catch (t) {
                    it.deleteExpando = !1
                }
            }
            e = null
        }(), st.acceptData = function (e) {
            var t = st.noData[(e.nodeName + " ").toLowerCase()],
                i = +e.nodeType || 1;
            return 1 !== i && 9 !== i ? !1 : !t || t !== !0 && e.getAttribute("classid") === t
        };
    var Ct = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,
        Dt = /([A-Z])/g;
    st.extend({
        "cache": {},
        "noData": {
            "applet ": !0,
            "embed ": !0,
            "object ": "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
        },
        "hasData": function (e) {
            return e = e.nodeType ? st.cache[e[st.expando]] : e[st.expando], !!e && !c(e)
        },
        "data": function (e, t, i) {
            return u(e, t, i)
        },
        "removeData": function (e, t) {
            return h(e, t)
        },
        "_data": function (e, t, i) {
            return u(e, t, i, !0)
        },
        "_removeData": function (e, t) {
            return h(e, t, !0)
        }
    }), st.fn.extend({
        "data": function (e, t) {
            var i, n, s, r = this[0],
                a = r && r.attributes;
            if (void 0 === e) {
                if (this.length && (s = st.data(r), 1 === r.nodeType && !st._data(r, "parsedAttrs"))) {
                    for (i = a.length; i--;) a[i] && (n = a[i].name, 0 === n.indexOf("data-") && (n = st.camelCase(n.slice(5)), l(r, n, s[n])));
                    st._data(r, "parsedAttrs", !0)
                }
                return s
            }
            return "object" == typeof e ? this.each(function () {
                st.data(this, e)
            }) : arguments.length > 1 ? this.each(function () {
                st.data(this, e, t)
            }) : r ? l(r, e, st.data(r, e)) : void 0
        },
        "removeData": function (e) {
            return this.each(function () {
                st.removeData(this, e)
            })
        }
    }), st.extend({
        "queue": function (e, t, i) {
            var n;
            return e ? (t = (t || "fx") + "queue", n = st._data(e, t), i && (!n || st.isArray(i) ? n = st._data(e, t, st.makeArray(i)) : n.push(i)), n || []) : void 0
        },
        "dequeue": function (e, t) {
            t = t || "fx";
            var i = st.queue(e, t),
                n = i.length,
                s = i.shift(),
                r = st._queueHooks(e, t),
                a = function () {
                    st.dequeue(e, t)
                };
            "inprogress" === s && (s = i.shift(), n--), s && ("fx" === t && i.unshift("inprogress"), delete r.stop, s.call(e, a, r)), !n && r && r.empty.fire()
        },
        "_queueHooks": function (e, t) {
            var i = t + "queueHooks";
            return st._data(e, i) || st._data(e, i, {
                "empty": st.Callbacks("once memory").add(function () {
                    st._removeData(e, t + "queue"), st._removeData(e, i)
                })
            })
        }
    }), st.fn.extend({
        "queue": function (e, t) {
            var i = 2;
            return "string" != typeof e && (t = e, e = "fx", i--), arguments.length < i ? st.queue(this[0], e) : void 0 === t ? this : this.each(function () {
                var i = st.queue(this, e, t);
                st._queueHooks(this, e), "fx" === e && "inprogress" !== i[0] && st.dequeue(this, e)
            })
        },
        "dequeue": function (e) {
            return this.each(function () {
                st.dequeue(this, e)
            })
        },
        "clearQueue": function (e) {
            return this.queue(e || "fx", [])
        },
        "promise": function (e, t) {
            var i, n = 1,
                s = st.Deferred(),
                r = this,
                a = this.length,
                o = function () {
                    --n || s.resolveWith(r, [r])
                };
            for ("string" != typeof e && (t = e, e = void 0), e = e || "fx"; a--;) i = st._data(r[a], e + "queueHooks"), i && i.empty && (n++, i.empty.add(o));
            return o(), s.promise(t)
        }
    });
    var St = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
        Tt = ["Top", "Right", "Bottom", "Left"],
        Et = function (e, t) {
            return e = t || e, "none" === st.css(e, "display") || !st.contains(e.ownerDocument, e)
        },
        Mt = st.access = function (e, t, i, n, s, r, a) {
            var o = 0,
                l = e.length,
                c = null == i;
            if ("object" === st.type(i)) {
                s = !0;
                for (o in i) st.access(e, t, o, i[o], !0, r, a)
            } else if (void 0 !== n && (s = !0, st.isFunction(n) || (a = !0), c && (a ? (t.call(e, n), t = null) : (c = t, t = function (e, t, i) {
                    return c.call(st(e), i)
                })), t))
                for (; l > o; o++) t(e[o], i, a ? n : n.call(e[o], o, t(e[o], i)));
            return s ? e : c ? t.call(e) : l ? t(e[0], i) : r
        },
        Nt = /^(?:checkbox|radio)$/i;
    ! function () {
        var e = ft.createElement("input"),
            t = ft.createElement("div"),
            i = ft.createDocumentFragment();
        if (t.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", it.leadingWhitespace = 3 === t.firstChild.nodeType, it.tbody = !t.getElementsByTagName("tbody").length, it.htmlSerialize = !!t.getElementsByTagName("link").length, it.html5Clone = "<:nav></:nav>" !== ft.createElement("nav").cloneNode(!0).outerHTML, e.type = "checkbox", e.checked = !0, i.appendChild(e), it.appendChecked = e.checked, t.innerHTML = "<textarea>x</textarea>", it.noCloneChecked = !!t.cloneNode(!0).lastChild.defaultValue, i.appendChild(t), t.innerHTML = "<input type='radio' checked='checked' name='t'/>", it.checkClone = t.cloneNode(!0).cloneNode(!0).lastChild.checked, it.noCloneEvent = !0, t.attachEvent && (t.attachEvent("onclick", function () {
                it.noCloneEvent = !1
            }), t.cloneNode(!0).click()), null == it.deleteExpando) {
            it.deleteExpando = !0;
            try {
                delete t.test
            } catch (n) {
                it.deleteExpando = !1
            }
        }
    }(),
    function () {
        var t, i, n = ft.createElement("div");
        for (t in {
                "submit": !0,
                "change": !0,
                "focusin": !0
            }) i = "on" + t, (it[t + "Bubbles"] = i in e) || (n.setAttribute(i, "t"), it[t + "Bubbles"] = n.attributes[i].expando === !1);
        n = null
    }();
    var At = /^(?:input|select|textarea)$/i,
        It = /^key/,
        jt = /^(?:mouse|pointer|contextmenu)|click/,
        Ot = /^(?:focusinfocus|focusoutblur)$/,
        Ft = /^([^.]*)(?:\.(.+)|)$/;
    st.event = {
        "global": {},
        "add": function (e, t, i, n, s) {
            var r, a, o, l, c, u, h, d, p, f, m, g = st._data(e);
            if (g) {
                for (i.handler && (l = i, i = l.handler, s = l.selector), i.guid || (i.guid = st.guid++), (a = g.events) || (a = g.events = {}), (u = g.handle) || (u = g.handle = function (e) {
                        return typeof st === _t || e && st.event.triggered === e.type ? void 0 : st.event.dispatch.apply(u.elem, arguments)
                    }, u.elem = e), t = (t || "").match(bt) || [""], o = t.length; o--;) r = Ft.exec(t[o]) || [], p = m = r[1], f = (r[2] || "").split(".").sort(), p && (c = st.event.special[p] || {}, p = (s ? c.delegateType : c.bindType) || p, c = st.event.special[p] || {}, h = st.extend({
                    "type": p,
                    "origType": m,
                    "data": n,
                    "handler": i,
                    "guid": i.guid,
                    "selector": s,
                    "needsContext": s && st.expr.match.needsContext.test(s),
                    "namespace": f.join(".")
                }, l), (d = a[p]) || (d = a[p] = [], d.delegateCount = 0, c.setup && c.setup.call(e, n, f, u) !== !1 || (e.addEventListener ? e.addEventListener(p, u, !1) : e.attachEvent && e.attachEvent("on" + p, u))), c.add && (c.add.call(e, h), h.handler.guid || (h.handler.guid = i.guid)), s ? d.splice(d.delegateCount++, 0, h) : d.push(h), st.event.global[p] = !0);
                e = null
            }
        },
        "remove": function (e, t, i, n, s) {
            var r, a, o, l, c, u, h, d, p, f, m, g = st.hasData(e) && st._data(e);
            if (g && (u = g.events)) {
                for (t = (t || "").match(bt) || [""], c = t.length; c--;)
                    if (o = Ft.exec(t[c]) || [], p = m = o[1], f = (o[2] || "").split(".").sort(), p) {
                        for (h = st.event.special[p] || {}, p = (n ? h.delegateType : h.bindType) || p, d = u[p] || [], o = o[2] && new RegExp("(^|\\.)" + f.join("\\.(?:.*\\.|)") + "(\\.|$)"), l = r = d.length; r--;) a = d[r], !s && m !== a.origType || i && i.guid !== a.guid || o && !o.test(a.namespace) || n && n !== a.selector && ("**" !== n || !a.selector) || (d.splice(r, 1), a.selector && d.delegateCount--, h.remove && h.remove.call(e, a));
                        l && !d.length && (h.teardown && h.teardown.call(e, f, g.handle) !== !1 || st.removeEvent(e, p, g.handle), delete u[p])
                    } else
                        for (p in u) st.event.remove(e, p + t[c], i, n, !0);
                st.isEmptyObject(u) && (delete g.handle, st._removeData(e, "events"))
            }
        },
        "trigger": function (t, i, n, s) {
            var r, a, o, l, c, u, h, d = [n || ft],
                p = tt.call(t, "type") ? t.type : t,
                f = tt.call(t, "namespace") ? t.namespace.split(".") : [];
            if (o = u = n = n || ft, 3 !== n.nodeType && 8 !== n.nodeType && !Ot.test(p + st.event.triggered) && (p.indexOf(".") >= 0 && (f = p.split("."), p = f.shift(), f.sort()), a = p.indexOf(":") < 0 && "on" + p, t = t[st.expando] ? t : new st.Event(p, "object" == typeof t && t), t.isTrigger = s ? 2 : 3, t.namespace = f.join("."), t.namespace_re = t.namespace ? new RegExp("(^|\\.)" + f.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, t.result = void 0, t.target || (t.target = n), i = null == i ? [t] : st.makeArray(i, [t]), c = st.event.special[p] || {}, s || !c.trigger || c.trigger.apply(n, i) !== !1)) {
                if (!s && !c.noBubble && !st.isWindow(n)) {
                    for (l = c.delegateType || p, Ot.test(l + p) || (o = o.parentNode); o; o = o.parentNode) d.push(o), u = o;
                    u === (n.ownerDocument || ft) && d.push(u.defaultView || u.parentWindow || e)
                }
                for (h = 0;
                    (o = d[h++]) && !t.isPropagationStopped();) t.type = h > 1 ? l : c.bindType || p, r = (st._data(o, "events") || {})[t.type] && st._data(o, "handle"), r && r.apply(o, i), r = a && o[a], r && r.apply && st.acceptData(o) && (t.result = r.apply(o, i), t.result === !1 && t.preventDefault());
                if (t.type = p, !s && !t.isDefaultPrevented() && (!c._default || c._default.apply(d.pop(), i) === !1) && st.acceptData(n) && a && n[p] && !st.isWindow(n)) {
                    u = n[a], u && (n[a] = null), st.event.triggered = p;
                    try {
                        n[p]()
                    } catch (m) {}
                    st.event.triggered = void 0, u && (n[a] = u)
                }
                return t.result
            }
        },
        "dispatch": function (e) {
            e = st.event.fix(e);
            var t, i, n, s, r, a = [],
                o = X.call(arguments),
                l = (st._data(this, "events") || {})[e.type] || [],
                c = st.event.special[e.type] || {};
            if (o[0] = e, e.delegateTarget = this, !c.preDispatch || c.preDispatch.call(this, e) !== !1) {
                for (a = st.event.handlers.call(this, e, l), t = 0;
                    (s = a[t++]) && !e.isPropagationStopped();)
                    for (e.currentTarget = s.elem, r = 0;
                        (n = s.handlers[r++]) && !e.isImmediatePropagationStopped();)(!e.namespace_re || e.namespace_re.test(n.namespace)) && (e.handleObj = n, e.data = n.data, i = ((st.event.special[n.origType] || {}).handle || n.handler).apply(s.elem, o), void 0 !== i && (e.result = i) === !1 && (e.preventDefault(), e.stopPropagation()));
                return c.postDispatch && c.postDispatch.call(this, e), e.result
            }
        },
        "handlers": function (e, t) {
            var i, n, s, r, a = [],
                o = t.delegateCount,
                l = e.target;
            if (o && l.nodeType && (!e.button || "click" !== e.type))
                for (; l != this; l = l.parentNode || this)
                    if (1 === l.nodeType && (l.disabled !== !0 || "click" !== e.type)) {
                        for (s = [], r = 0; o > r; r++) n = t[r], i = n.selector + " ", void 0 === s[i] && (s[i] = n.needsContext ? st(i, this).index(l) >= 0 : st.find(i, this, null, [l]).length), s[i] && s.push(n);
                        s.length && a.push({
                            "elem": l,
                            "handlers": s
                        })
                    }
            return o < t.length && a.push({
                "elem": this,
                "handlers": t.slice(o)
            }), a
        },
        "fix": function (e) {
            if (e[st.expando]) return e;
            var t, i, n, s = e.type,
                r = e,
                a = this.fixHooks[s];
            for (a || (this.fixHooks[s] = a = jt.test(s) ? this.mouseHooks : It.test(s) ? this.keyHooks : {}), n = a.props ? this.props.concat(a.props) : this.props, e = new st.Event(r), t = n.length; t--;) i = n[t], e[i] = r[i];
            return e.target || (e.target = r.srcElement || ft), 3 === e.target.nodeType && (e.target = e.target.parentNode), e.metaKey = !!e.metaKey, a.filter ? a.filter(e, r) : e
        },
        "props": "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
        "fixHooks": {},
        "keyHooks": {
            "props": "char charCode key keyCode".split(" "),
            "filter": function (e, t) {
                return null == e.which && (e.which = null != t.charCode ? t.charCode : t.keyCode), e
            }
        },
        "mouseHooks": {
            "props": "button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
            "filter": function (e, t) {
                var i, n, s, r = t.button,
                    a = t.fromElement;
                return null == e.pageX && null != t.clientX && (n = e.target.ownerDocument || ft, s = n.documentElement, i = n.body, e.pageX = t.clientX + (s && s.scrollLeft || i && i.scrollLeft || 0) - (s && s.clientLeft || i && i.clientLeft || 0), e.pageY = t.clientY + (s && s.scrollTop || i && i.scrollTop || 0) - (s && s.clientTop || i && i.clientTop || 0)), !e.relatedTarget && a && (e.relatedTarget = a === e.target ? t.toElement : a), e.which || void 0 === r || (e.which = 1 & r ? 1 : 2 & r ? 3 : 4 & r ? 2 : 0), e
            }
        },
        "special": {
            "load": {
                "noBubble": !0
            },
            "focus": {
                "trigger": function () {
                    if (this !== f() && this.focus) try {
                        return this.focus(), !1
                    } catch (e) {}
                },
                "delegateType": "focusin"
            },
            "blur": {
                "trigger": function () {
                    return this === f() && this.blur ? (this.blur(), !1) : void 0
                },
                "delegateType": "focusout"
            },
            "click": {
                "trigger": function () {
                    return st.nodeName(this, "input") && "checkbox" === this.type && this.click ? (this.click(), !1) : void 0
                },
                "_default": function (e) {
                    return st.nodeName(e.target, "a")
                }
            },
            "beforeunload": {
                "postDispatch": function (e) {
                    void 0 !== e.result && e.originalEvent && (e.originalEvent.returnValue = e.result)
                }
            }
        },
        "simulate": function (e, t, i, n) {
            var s = st.extend(new st.Event, i, {
                "type": e,
                "isSimulated": !0,
                "originalEvent": {}
            });
            n ? st.event.trigger(s, null, t) : st.event.dispatch.call(t, s), s.isDefaultPrevented() && i.preventDefault()
        }
    }, st.removeEvent = ft.removeEventListener ? function (e, t, i) {
        e.removeEventListener && e.removeEventListener(t, i, !1)
    } : function (e, t, i) {
        var n = "on" + t;
        e.detachEvent && (typeof e[n] === _t && (e[n] = null), e.detachEvent(n, i))
    }, st.Event = function (e, t) {
        return this instanceof st.Event ? (e && e.type ? (this.originalEvent = e, this.type = e.type, this.isDefaultPrevented = e.defaultPrevented || void 0 === e.defaultPrevented && e.returnValue === !1 ? d : p) : this.type = e, t && st.extend(this, t), this.timeStamp = e && e.timeStamp || st.now(), void(this[st.expando] = !0)) : new st.Event(e, t)
    }, st.Event.prototype = {
        "isDefaultPrevented": p,
        "isPropagationStopped": p,
        "isImmediatePropagationStopped": p,
        "preventDefault": function () {
            var e = this.originalEvent;
            this.isDefaultPrevented = d, e && (e.preventDefault ? e.preventDefault() : e.returnValue = !1)
        },
        "stopPropagation": function () {
            var e = this.originalEvent;
            this.isPropagationStopped = d, e && (e.stopPropagation && e.stopPropagation(), e.cancelBubble = !0)
        },
        "stopImmediatePropagation": function () {
            var e = this.originalEvent;
            this.isImmediatePropagationStopped = d, e && e.stopImmediatePropagation && e.stopImmediatePropagation(), this.stopPropagation()
        }
    }, st.each({
        "mouseenter": "mouseover",
        "mouseleave": "mouseout",
        "pointerenter": "pointerover",
        "pointerleave": "pointerout"
    }, function (e, t) {
        st.event.special[e] = {
            "delegateType": t,
            "bindType": t,
            "handle": function (e) {
                var i, n = this,
                    s = e.relatedTarget,
                    r = e.handleObj;
                return (!s || s !== n && !st.contains(n, s)) && (e.type = r.origType, i = r.handler.apply(this, arguments), e.type = t), i
            }
        }
    }), it.submitBubbles || (st.event.special.submit = {
        "setup": function () {
            return st.nodeName(this, "form") ? !1 : void st.event.add(this, "click._submit keypress._submit", function (e) {
                var t = e.target,
                    i = st.nodeName(t, "input") || st.nodeName(t, "button") ? t.form : void 0;
                i && !st._data(i, "submitBubbles") && (st.event.add(i, "submit._submit", function (e) {
                    e._submit_bubble = !0
                }), st._data(i, "submitBubbles", !0))
            })
        },
        "postDispatch": function (e) {
            e._submit_bubble && (delete e._submit_bubble, this.parentNode && !e.isTrigger && st.event.simulate("submit", this.parentNode, e, !0))
        },
        "teardown": function () {
            return st.nodeName(this, "form") ? !1 : void st.event.remove(this, "._submit")
        }
    }), it.changeBubbles || (st.event.special.change = {
        "setup": function () {
            return At.test(this.nodeName) ? (("checkbox" === this.type || "radio" === this.type) && (st.event.add(this, "propertychange._change", function (e) {
                "checked" === e.originalEvent.propertyName && (this._just_changed = !0)
            }), st.event.add(this, "click._change", function (e) {
                this._just_changed && !e.isTrigger && (this._just_changed = !1), st.event.simulate("change", this, e, !0)
            })), !1) : void st.event.add(this, "beforeactivate._change", function (e) {
                var t = e.target;
                At.test(t.nodeName) && !st._data(t, "changeBubbles") && (st.event.add(t, "change._change", function (e) {
                    !this.parentNode || e.isSimulated || e.isTrigger || st.event.simulate("change", this.parentNode, e, !0)
                }), st._data(t, "changeBubbles", !0))
            })
        },
        "handle": function (e) {
            var t = e.target;
            return this !== t || e.isSimulated || e.isTrigger || "radio" !== t.type && "checkbox" !== t.type ? e.handleObj.handler.apply(this, arguments) : void 0
        },
        "teardown": function () {
            return st.event.remove(this, "._change"), !At.test(this.nodeName)
        }
    }), it.focusinBubbles || st.each({
        "focus": "focusin",
        "blur": "focusout"
    }, function (e, t) {
        var i = function (e) {
            st.event.simulate(t, e.target, st.event.fix(e), !0)
        };
        st.event.special[t] = {
            "setup": function () {
                var n = this.ownerDocument || this,
                    s = st._data(n, t);
                s || n.addEventListener(e, i, !0), st._data(n, t, (s || 0) + 1)
            },
            "teardown": function () {
                var n = this.ownerDocument || this,
                    s = st._data(n, t) - 1;
                s ? st._data(n, t, s) : (n.removeEventListener(e, i, !0), st._removeData(n, t))
            }
        }
    }), st.fn.extend({
        "on": function (e, t, i, n, s) {
            var r, a;
            if ("object" == typeof e) {
                "string" != typeof t && (i = i || t, t = void 0);
                for (r in e) this.on(r, t, i, e[r], s);
                return this
            }
            if (null == i && null == n ? (n = t, i = t = void 0) : null == n && ("string" == typeof t ? (n = i, i = void 0) : (n = i, i = t, t = void 0)), n === !1) n = p;
            else if (!n) return this;
            return 1 === s && (a = n, n = function (e) {
                return st().off(e), a.apply(this, arguments)
            }, n.guid = a.guid || (a.guid = st.guid++)), this.each(function () {
                st.event.add(this, e, n, i, t)
            })
        },
        "one": function (e, t, i, n) {
            return this.on(e, t, i, n, 1)
        },
        "off": function (e, t, i) {
            var n, s;
            if (e && e.preventDefault && e.handleObj) return n = e.handleObj, st(e.delegateTarget).off(n.namespace ? n.origType + "." + n.namespace : n.origType, n.selector, n.handler), this;
            if ("object" == typeof e) {
                for (s in e) this.off(s, t, e[s]);
                return this
            }
            return (t === !1 || "function" == typeof t) && (i = t, t = void 0), i === !1 && (i = p), this.each(function () {
                st.event.remove(this, e, i, t)
            })
        },
        "trigger": function (e, t) {
            return this.each(function () {
                st.event.trigger(e, t, this)
            })
        },
        "triggerHandler": function (e, t) {
            var i = this[0];
            return i ? st.event.trigger(e, t, i, !0) : void 0
        }
    });
    var Lt = "abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",
        Pt = / jQuery\d+="(?:null|\d+)"/g,
        Ht = new RegExp("<(?:" + Lt + ")[\\s/>]", "i"),
        Rt = /^\s+/,
        Wt = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,
        qt = /<([\w:]+)/,
        zt = /<tbody/i,
        Bt = /<|&#?\w+;/,
        Yt = /<(?:script|style|link)/i,
        Ut = /checked\s*(?:[^=]|=\s*.checked.)/i,
        Kt = /^$|\/(?:java|ecma)script/i,
        $t = /^true\/(.*)/,
        Vt = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,
        Xt = {
            "option": [1, "<select multiple='multiple'>", "</select>"],
            "legend": [1, "<fieldset>", "</fieldset>"],
            "area": [1, "<map>", "</map>"],
            "param": [1, "<object>", "</object>"],
            "thead": [1, "<table>", "</table>"],
            "tr": [2, "<table><tbody>", "</tbody></table>"],
            "col": [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"],
            "td": [3, "<table><tbody><tr>", "</tr></tbody></table>"],
            "_default": it.htmlSerialize ? [0, "", ""] : [1, "X<div>", "</div>"]
        },
        Gt = m(ft),
        Jt = Gt.appendChild(ft.createElement("div"));
    Xt.optgroup = Xt.option, Xt.tbody = Xt.tfoot = Xt.colgroup = Xt.caption = Xt.thead, Xt.th = Xt.td, st.extend({
        "clone": function (e, t, i) {
            var n, s, r, a, o, l = st.contains(e.ownerDocument, e);
            if (it.html5Clone || st.isXMLDoc(e) || !Ht.test("<" + e.nodeName + ">") ? r = e.cloneNode(!0) : (Jt.innerHTML = e.outerHTML, Jt.removeChild(r = Jt.firstChild)), !(it.noCloneEvent && it.noCloneChecked || 1 !== e.nodeType && 11 !== e.nodeType || st.isXMLDoc(e)))
                for (n = g(r), o = g(e), a = 0; null != (s = o[a]); ++a) n[a] && _(s, n[a]);
            if (t)
                if (i)
                    for (o = o || g(e), n = n || g(r), a = 0; null != (s = o[a]); a++) k(s, n[a]);
                else k(e, r);
            return n = g(r, "script"), n.length > 0 && x(n, !l && g(e, "script")), n = o = s = null, r
        },
        "buildFragment": function (e, t, i, n) {
            for (var s, r, a, o, l, c, u, h = e.length, d = m(t), p = [], f = 0; h > f; f++)
                if (r = e[f], r || 0 === r)
                    if ("object" === st.type(r)) st.merge(p, r.nodeType ? [r] : r);
                    else if (Bt.test(r)) {
                for (o = o || d.appendChild(t.createElement("div")), l = (qt.exec(r) || ["", ""])[1].toLowerCase(), u = Xt[l] || Xt._default, o.innerHTML = u[1] + r.replace(Wt, "<$1></$2>") + u[2], s = u[0]; s--;) o = o.lastChild;
                if (!it.leadingWhitespace && Rt.test(r) && p.push(t.createTextNode(Rt.exec(r)[0])), !it.tbody)
                    for (r = "table" !== l || zt.test(r) ? "<table>" !== u[1] || zt.test(r) ? 0 : o : o.firstChild, s = r && r.childNodes.length; s--;) st.nodeName(c = r.childNodes[s], "tbody") && !c.childNodes.length && r.removeChild(c);
                for (st.merge(p, o.childNodes), o.textContent = ""; o.firstChild;) o.removeChild(o.firstChild);
                o = d.lastChild
            } else p.push(t.createTextNode(r));
            for (o && d.removeChild(o), it.appendChecked || st.grep(g(p, "input"), v), f = 0; r = p[f++];)
                if ((!n || -1 === st.inArray(r, n)) && (a = st.contains(r.ownerDocument, r), o = g(d.appendChild(r), "script"), a && x(o), i))
                    for (s = 0; r = o[s++];) Kt.test(r.type || "") && i.push(r);
            return o = null, d
        },
        "cleanData": function (e, t) {
            for (var i, n, s, r, a = 0, o = st.expando, l = st.cache, c = it.deleteExpando, u = st.event.special; null != (i = e[a]); a++)
                if ((t || st.acceptData(i)) && (s = i[o], r = s && l[s])) {
                    if (r.events)
                        for (n in r.events) u[n] ? st.event.remove(i, n) : st.removeEvent(i, n, r.handle);
                    l[s] && (delete l[s], c ? delete i[o] : typeof i.removeAttribute !== _t ? i.removeAttribute(o) : i[o] = null, V.push(s))
                }
        }
    }), st.fn.extend({
        "text": function (e) {
            return Mt(this, function (e) {
                return void 0 === e ? st.text(this) : this.empty().append((this[0] && this[0].ownerDocument || ft).createTextNode(e))
            }, null, e, arguments.length)
        },
        "append": function () {
            return this.domManip(arguments, function (e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = y(this, e);
                    t.appendChild(e)
                }
            })
        },
        "prepend": function () {
            return this.domManip(arguments, function (e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = y(this, e);
                    t.insertBefore(e, t.firstChild)
                }
            })
        },
        "before": function () {
            return this.domManip(arguments, function (e) {
                this.parentNode && this.parentNode.insertBefore(e, this)
            })
        },
        "after": function () {
            return this.domManip(arguments, function (e) {
                this.parentNode && this.parentNode.insertBefore(e, this.nextSibling)
            })
        },
        "remove": function (e, t) {
            for (var i, n = e ? st.filter(e, this) : this, s = 0; null != (i = n[s]); s++) t || 1 !== i.nodeType || st.cleanData(g(i)), i.parentNode && (t && st.contains(i.ownerDocument, i) && x(g(i, "script")), i.parentNode.removeChild(i));
            return this
        },
        "empty": function () {
            for (var e, t = 0; null != (e = this[t]); t++) {
                for (1 === e.nodeType && st.cleanData(g(e, !1)); e.firstChild;) e.removeChild(e.firstChild);
                e.options && st.nodeName(e, "select") && (e.options.length = 0)
            }
            return this
        },
        "clone": function (e, t) {
            return e = null == e ? !1 : e, t = null == t ? e : t, this.map(function () {
                return st.clone(this, e, t)
            })
        },
        "html": function (e) {
            return Mt(this, function (e) {
                var t = this[0] || {},
                    i = 0,
                    n = this.length;
                if (void 0 === e) return 1 === t.nodeType ? t.innerHTML.replace(Pt, "") : void 0;
                if (!("string" != typeof e || Yt.test(e) || !it.htmlSerialize && Ht.test(e) || !it.leadingWhitespace && Rt.test(e) || Xt[(qt.exec(e) || ["", ""])[1].toLowerCase()])) {
                    e = e.replace(Wt, "<$1></$2>");
                    try {
                        for (; n > i; i++) t = this[i] || {}, 1 === t.nodeType && (st.cleanData(g(t, !1)), t.innerHTML = e);
                        t = 0
                    } catch (s) {}
                }
                t && this.empty().append(e)
            }, null, e, arguments.length)
        },
        "replaceWith": function () {
            var e = arguments[0];
            return this.domManip(arguments, function (t) {
                e = this.parentNode, st.cleanData(g(this)), e && e.replaceChild(t, this)
            }), e && (e.length || e.nodeType) ? this : this.remove()
        },
        "detach": function (e) {
            return this.remove(e, !0)
        },
        "domManip": function (e, t) {
            e = G.apply([], e);
            var i, n, s, r, a, o, l = 0,
                c = this.length,
                u = this,
                h = c - 1,
                d = e[0],
                p = st.isFunction(d);
            if (p || c > 1 && "string" == typeof d && !it.checkClone && Ut.test(d)) return this.each(function (i) {
                var n = u.eq(i);
                p && (e[0] = d.call(this, i, n.html())), n.domManip(e, t)
            });
            if (c && (o = st.buildFragment(e, this[0].ownerDocument, !1, this), i = o.firstChild, 1 === o.childNodes.length && (o = i), i)) {
                for (r = st.map(g(o, "script"), b), s = r.length; c > l; l++) n = o, l !== h && (n = st.clone(n, !0, !0), s && st.merge(r, g(n, "script"))), t.call(this[l], n, l);
                if (s)
                    for (a = r[r.length - 1].ownerDocument, st.map(r, w), l = 0; s > l; l++) n = r[l], Kt.test(n.type || "") && !st._data(n, "globalEval") && st.contains(a, n) && (n.src ? st._evalUrl && st._evalUrl(n.src) : st.globalEval((n.text || n.textContent || n.innerHTML || "").replace(Vt, "")));
                o = i = null
            }
            return this
        }
    }), st.each({
        "appendTo": "append",
        "prependTo": "prepend",
        "insertBefore": "before",
        "insertAfter": "after",
        "replaceAll": "replaceWith"
    }, function (e, t) {
        st.fn[e] = function (e) {
            for (var i, n = 0, s = [], r = st(e), a = r.length - 1; a >= n; n++) i = n === a ? this : this.clone(!0), st(r[n])[t](i), J.apply(s, i.get());
            return this.pushStack(s)
        }
    });
    var Qt, Zt = {};
    ! function () {
        var e;
        it.shrinkWrapBlocks = function () {
            if (null != e) return e;
            e = !1;
            var t, i, n;
            return i = ft.getElementsByTagName("body")[0], i && i.style ? (t = ft.createElement("div"), n = ft.createElement("div"), n.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", i.appendChild(n).appendChild(t), typeof t.style.zoom !== _t && (t.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:1px;width:1px;zoom:1", t.appendChild(ft.createElement("div")).style.width = "5px", e = 3 !== t.offsetWidth), i.removeChild(n), e) : void 0
        }
    }();
    var ei, ti, ii = /^margin/,
        ni = new RegExp("^(" + St + ")(?!px)[a-z%]+$", "i"),
        si = /^(top|right|bottom|left)$/;
    e.getComputedStyle ? (ei = function (e) {
            return e.ownerDocument.defaultView.getComputedStyle(e, null)
        }, ti = function (e, t, i) {
            var n, s, r, a, o = e.style;
            return i = i || ei(e), a = i ? i.getPropertyValue(t) || i[t] : void 0, i && ("" !== a || st.contains(e.ownerDocument, e) || (a = st.style(e, t)), ni.test(a) && ii.test(t) && (n = o.width, s = o.minWidth, r = o.maxWidth, o.minWidth = o.maxWidth = o.width = a, a = i.width, o.width = n, o.minWidth = s, o.maxWidth = r)), void 0 === a ? a : a + ""
        }) : ft.documentElement.currentStyle && (ei = function (e) {
            return e.currentStyle
        }, ti = function (e, t, i) {
            var n, s, r, a, o = e.style;
            return i = i || ei(e), a = i ? i[t] : void 0, null == a && o && o[t] && (a = o[t]), ni.test(a) && !si.test(t) && (n = o.left, s = e.runtimeStyle, r = s && s.left, r && (s.left = e.currentStyle.left), o.left = "fontSize" === t ? "1em" : a, a = o.pixelLeft + "px", o.left = n, r && (s.left = r)), void 0 === a ? a : a + "" || "auto"
        }),
        function () {
            function t() {
                var t, i, n, s;
                i = ft.getElementsByTagName("body")[0], i && i.style && (t = ft.createElement("div"), n = ft.createElement("div"), n.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", i.appendChild(n).appendChild(t), t.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:block;margin-top:1%;top:1%;border:1px;padding:1px;width:4px;position:absolute", r = a = !1, l = !0, e.getComputedStyle && (r = "1%" !== (e.getComputedStyle(t, null) || {}).top, a = "4px" === (e.getComputedStyle(t, null) || {
                    "width": "4px"
                }).width, s = t.appendChild(ft.createElement("div")), s.style.cssText = t.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:0", s.style.marginRight = s.style.width = "0", t.style.width = "1px", l = !parseFloat((e.getComputedStyle(s, null) || {}).marginRight)), t.innerHTML = "<table><tr><td></td><td>t</td></tr></table>", s = t.getElementsByTagName("td"), s[0].style.cssText = "margin:0;border:0;padding:0;display:none", o = 0 === s[0].offsetHeight, o && (s[0].style.display = "", s[1].style.display = "none", o = 0 === s[0].offsetHeight), i.removeChild(n))
            }
            var i, n, s, r, a, o, l;
            i = ft.createElement("div"), i.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", s = i.getElementsByTagName("a")[0], n = s && s.style, n && (n.cssText = "float:left;opacity:.5", it.opacity = "0.5" === n.opacity, it.cssFloat = !!n.cssFloat, i.style.backgroundClip = "content-box", i.cloneNode(!0).style.backgroundClip = "", it.clearCloneStyle = "content-box" === i.style.backgroundClip, it.boxSizing = "" === n.boxSizing || "" === n.MozBoxSizing || "" === n.WebkitBoxSizing, st.extend(it, {
                "reliableHiddenOffsets": function () {
                    return null == o && t(), o
                },
                "boxSizingReliable": function () {
                    return null == a && t(), a
                },
                "pixelPosition": function () {
                    return null == r && t(), r
                },
                "reliableMarginRight": function () {
                    return null == l && t(), l
                }
            }))
        }(), st.swap = function (e, t, i, n) {
            var s, r, a = {};
            for (r in t) a[r] = e.style[r], e.style[r] = t[r];
            s = i.apply(e, n || []);
            for (r in t) e.style[r] = a[r];
            return s
        };
    var ri = /alpha\([^)]*\)/i,
        ai = /opacity\s*=\s*([^)]*)/,
        oi = /^(none|table(?!-c[ea]).+)/,
        li = new RegExp("^(" + St + ")(.*)$", "i"),
        ci = new RegExp("^([+-])=(" + St + ")", "i"),
        ui = {
            "position": "absolute",
            "visibility": "hidden",
            "display": "block"
        },
        hi = {
            "letterSpacing": "0",
            "fontWeight": "400"
        },
        di = ["Webkit", "O", "Moz", "ms"];
    st.extend({
        "cssHooks": {
            "opacity": {
                "get": function (e, t) {
                    if (t) {
                        var i = ti(e, "opacity");
                        return "" === i ? "1" : i
                    }
                }
            }
        },
        "cssNumber": {
            "columnCount": !0,
            "fillOpacity": !0,
            "flexGrow": !0,
            "flexShrink": !0,
            "fontWeight": !0,
            "lineHeight": !0,
            "opacity": !0,
            "order": !0,
            "orphans": !0,
            "widows": !0,
            "zIndex": !0,
            "zoom": !0
        },
        "cssProps": {
            "float": it.cssFloat ? "cssFloat" : "styleFloat"
        },
        "style": function (e, t, i, n) {
            if (e && 3 !== e.nodeType && 8 !== e.nodeType && e.style) {
                var s, r, a, o = st.camelCase(t),
                    l = e.style;
                if (t = st.cssProps[o] || (st.cssProps[o] = T(l, o)), a = st.cssHooks[t] || st.cssHooks[o], void 0 === i) return a && "get" in a && void 0 !== (s = a.get(e, !1, n)) ? s : l[t];
                if (r = typeof i, "string" === r && (s = ci.exec(i)) && (i = (s[1] + 1) * s[2] + parseFloat(st.css(e, t)), r = "number"), null != i && i === i && ("number" !== r || st.cssNumber[o] || (i += "px"), it.clearCloneStyle || "" !== i || 0 !== t.indexOf("background") || (l[t] = "inherit"), !(a && "set" in a && void 0 === (i = a.set(e, i, n))))) try {
                    l[t] = i
                } catch (c) {}
            }
        },
        "css": function (e, t, i, n) {
            var s, r, a, o = st.camelCase(t);
            return t = st.cssProps[o] || (st.cssProps[o] = T(e.style, o)), a = st.cssHooks[t] || st.cssHooks[o], a && "get" in a && (r = a.get(e, !0, i)), void 0 === r && (r = ti(e, t, n)), "normal" === r && t in hi && (r = hi[t]), "" === i || i ? (s = parseFloat(r), i === !0 || st.isNumeric(s) ? s || 0 : r) : r
        }
    }), st.each(["height", "width"], function (e, t) {
        st.cssHooks[t] = {
            "get": function (e, i, n) {
                return i ? oi.test(st.css(e, "display")) && 0 === e.offsetWidth ? st.swap(e, ui, function () {
                    return A(e, t, n)
                }) : A(e, t, n) : void 0
            },
            "set": function (e, i, n) {
                var s = n && ei(e);
                return M(e, i, n ? N(e, t, n, it.boxSizing && "border-box" === st.css(e, "boxSizing", !1, s), s) : 0)
            }
        }
    }), it.opacity || (st.cssHooks.opacity = {
        "get": function (e, t) {
            return ai.test((t && e.currentStyle ? e.currentStyle.filter : e.style.filter) || "") ? .01 * parseFloat(RegExp.$1) + "" : t ? "1" : ""
        },
        "set": function (e, t) {
            var i = e.style,
                n = e.currentStyle,
                s = st.isNumeric(t) ? "alpha(opacity=" + 100 * t + ")" : "",
                r = n && n.filter || i.filter || "";
            i.zoom = 1, (t >= 1 || "" === t) && "" === st.trim(r.replace(ri, "")) && i.removeAttribute && (i.removeAttribute("filter"), "" === t || n && !n.filter) || (i.filter = ri.test(r) ? r.replace(ri, s) : r + " " + s)
        }
    }), st.cssHooks.marginRight = S(it.reliableMarginRight, function (e, t) {
        return t ? st.swap(e, {
            "display": "inline-block"
        }, ti, [e, "marginRight"]) : void 0
    }), st.each({
        "margin": "",
        "padding": "",
        "border": "Width"
    }, function (e, t) {
        st.cssHooks[e + t] = {
            "expand": function (i) {
                for (var n = 0, s = {}, r = "string" == typeof i ? i.split(" ") : [i]; 4 > n; n++) s[e + Tt[n] + t] = r[n] || r[n - 2] || r[0];
                return s
            }
        }, ii.test(e) || (st.cssHooks[e + t].set = M)
    }), st.fn.extend({
        "css": function (e, t) {
            return Mt(this, function (e, t, i) {
                var n, s, r = {},
                    a = 0;
                if (st.isArray(t)) {
                    for (n = ei(e), s = t.length; s > a; a++) r[t[a]] = st.css(e, t[a], !1, n);
                    return r
                }
                return void 0 !== i ? st.style(e, t, i) : st.css(e, t)
            }, e, t, arguments.length > 1)
        },
        "show": function () {
            return E(this, !0)
        },
        "hide": function () {
            return E(this)
        },
        "toggle": function (e) {
            return "boolean" == typeof e ? e ? this.show() : this.hide() : this.each(function () {
                Et(this) ? st(this).show() : st(this).hide()
            })
        }
    }), st.Tween = I, I.prototype = {
        "constructor": I,
        "init": function (e, t, i, n, s, r) {
            this.elem = e, this.prop = i, this.easing = s || "swing", this.options = t, this.start = this.now = this.cur(), this.end = n, this.unit = r || (st.cssNumber[i] ? "" : "px")
        },
        "cur": function () {
            var e = I.propHooks[this.prop];
            return e && e.get ? e.get(this) : I.propHooks._default.get(this)
        },
        "run": function (e) {
            var t, i = I.propHooks[this.prop];
            return this.pos = t = this.options.duration ? st.easing[this.easing](e, this.options.duration * e, 0, 1, this.options.duration) : e, this.now = (this.end - this.start) * t + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), i && i.set ? i.set(this) : I.propHooks._default.set(this), this
        }
    }, I.prototype.init.prototype = I.prototype, I.propHooks = {
        "_default": {
            "get": function (e) {
                var t;
                return null == e.elem[e.prop] || e.elem.style && null != e.elem.style[e.prop] ? (t = st.css(e.elem, e.prop, ""), t && "auto" !== t ? t : 0) : e.elem[e.prop]
            },
            "set": function (e) {
                st.fx.step[e.prop] ? st.fx.step[e.prop](e) : e.elem.style && (null != e.elem.style[st.cssProps[e.prop]] || st.cssHooks[e.prop]) ? st.style(e.elem, e.prop, e.now + e.unit) : e.elem[e.prop] = e.now
            }
        }
    }, I.propHooks.scrollTop = I.propHooks.scrollLeft = {
        "set": function (e) {
            e.elem.nodeType && e.elem.parentNode && (e.elem[e.prop] = e.now)
        }
    }, st.easing = {
        "linear": function (e) {
            return e
        },
        "swing": function (e) {
            return .5 - Math.cos(e * Math.PI) / 2
        }
    }, st.fx = I.prototype.init, st.fx.step = {};
    var pi, fi, mi = /^(?:toggle|show|hide)$/,
        gi = new RegExp("^(?:([+-])=|)(" + St + ")([a-z%]*)$", "i"),
        vi = /queueHooks$/,
        yi = [L],
        bi = {
            "*": [function (e, t) {
                var i = this.createTween(e, t),
                    n = i.cur(),
                    s = gi.exec(t),
                    r = s && s[3] || (st.cssNumber[e] ? "" : "px"),
                    a = (st.cssNumber[e] || "px" !== r && +n) && gi.exec(st.css(i.elem, e)),
                    o = 1,
                    l = 20;
                if (a && a[3] !== r) {
                    r = r || a[3], s = s || [], a = +n || 1;
                    do o = o || ".5", a /= o, st.style(i.elem, e, a + r); while (o !== (o = i.cur() / n) && 1 !== o && --l)
                }
                return s && (a = i.start = +a || +n || 0, i.unit = r, i.end = s[1] ? a + (s[1] + 1) * s[2] : +s[2]), i
            }]
        };
    st.Animation = st.extend(H, {
            "tweener": function (e, t) {
                st.isFunction(e) ? (t = e, e = ["*"]) : e = e.split(" ");
                for (var i, n = 0, s = e.length; s > n; n++) i = e[n], bi[i] = bi[i] || [], bi[i].unshift(t)
            },
            "prefilter": function (e, t) {
                t ? yi.unshift(e) : yi.push(e)
            }
        }), st.speed = function (e, t, i) {
            var n = e && "object" == typeof e ? st.extend({}, e) : {
                "complete": i || !i && t || st.isFunction(e) && e,
                "duration": e,
                "easing": i && t || t && !st.isFunction(t) && t
            };
            return n.duration = st.fx.off ? 0 : "number" == typeof n.duration ? n.duration : n.duration in st.fx.speeds ? st.fx.speeds[n.duration] : st.fx.speeds._default, (null == n.queue || n.queue === !0) && (n.queue = "fx"), n.old = n.complete, n.complete = function () {
                st.isFunction(n.old) && n.old.call(this), n.queue && st.dequeue(this, n.queue)
            }, n
        }, st.fn.extend({
            "fadeTo": function (e, t, i, n) {
                return this.filter(Et).css("opacity", 0).show().end().animate({
                    "opacity": t
                }, e, i, n)
            },
            "animate": function (e, t, i, n) {
                var s = st.isEmptyObject(e),
                    r = st.speed(t, i, n),
                    a = function () {
                        var t = H(this, st.extend({}, e), r);
                        (s || st._data(this, "finish")) && t.stop(!0)
                    };
                return a.finish = a, s || r.queue === !1 ? this.each(a) : this.queue(r.queue, a)
            },
            "stop": function (e, t, i) {
                var n = function (e) {
                    var t = e.stop;
                    delete e.stop, t(i)
                };
                return "string" != typeof e && (i = t, t = e, e = void 0), t && e !== !1 && this.queue(e || "fx", []), this.each(function () {
                    var t = !0,
                        s = null != e && e + "queueHooks",
                        r = st.timers,
                        a = st._data(this);
                    if (s) a[s] && a[s].stop && n(a[s]);
                    else
                        for (s in a) a[s] && a[s].stop && vi.test(s) && n(a[s]);
                    for (s = r.length; s--;) r[s].elem !== this || null != e && r[s].queue !== e || (r[s].anim.stop(i), t = !1, r.splice(s, 1));
                    (t || !i) && st.dequeue(this, e)
                })
            },
            "finish": function (e) {
                return e !== !1 && (e = e || "fx"), this.each(function () {
                    var t, i = st._data(this),
                        n = i[e + "queue"],
                        s = i[e + "queueHooks"],
                        r = st.timers,
                        a = n ? n.length : 0;
                    for (i.finish = !0, st.queue(this, e, []), s && s.stop && s.stop.call(this, !0), t = r.length; t--;) r[t].elem === this && r[t].queue === e && (r[t].anim.stop(!0), r.splice(t, 1));
                    for (t = 0; a > t; t++) n[t] && n[t].finish && n[t].finish.call(this);
                    delete i.finish
                })
            }
        }), st.each(["toggle", "show", "hide"], function (e, t) {
            var i = st.fn[t];
            st.fn[t] = function (e, n, s) {
                return null == e || "boolean" == typeof e ? i.apply(this, arguments) : this.animate(O(t, !0), e, n, s)
            }
        }), st.each({
            "slideDown": O("show"),
            "slideUp": O("hide"),
            "slideToggle": O("toggle"),
            "fadeIn": {
                "opacity": "show"
            },
            "fadeOut": {
                "opacity": "hide"
            },
            "fadeToggle": {
                "opacity": "toggle"
            }
        }, function (e, t) {
            st.fn[e] = function (e, i, n) {
                return this.animate(t, e, i, n)
            }
        }), st.timers = [], st.fx.tick = function () {
            var e, t = st.timers,
                i = 0;
            for (pi = st.now(); i < t.length; i++) e = t[i], e() || t[i] !== e || t.splice(i--, 1);
            t.length || st.fx.stop(), pi = void 0
        }, st.fx.timer = function (e) {
            st.timers.push(e), e() ? st.fx.start() : st.timers.pop()
        }, st.fx.interval = 13, st.fx.start = function () {
            fi || (fi = setInterval(st.fx.tick, st.fx.interval))
        }, st.fx.stop = function () {
            clearInterval(fi), fi = null
        }, st.fx.speeds = {
            "slow": 600,
            "fast": 200,
            "_default": 400
        }, st.fn.delay = function (e, t) {
            return e = st.fx ? st.fx.speeds[e] || e : e, t = t || "fx", this.queue(t, function (t, i) {
                var n = setTimeout(t, e);
                i.stop = function () {
                    clearTimeout(n)
                }
            })
        },
        function () {
            var e, t, i, n, s;
            t = ft.createElement("div"), t.setAttribute("className", "t"), t.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", n = t.getElementsByTagName("a")[0], i = ft.createElement("select"), s = i.appendChild(ft.createElement("option")), e = t.getElementsByTagName("input")[0], n.style.cssText = "top:1px", it.getSetAttribute = "t" !== t.className, it.style = /top/.test(n.getAttribute("style")), it.hrefNormalized = "/a" === n.getAttribute("href"), it.checkOn = !!e.value, it.optSelected = s.selected, it.enctype = !!ft.createElement("form").enctype, i.disabled = !0, it.optDisabled = !s.disabled, e = ft.createElement("input"), e.setAttribute("value", ""), it.input = "" === e.getAttribute("value"), e.value = "t", e.setAttribute("type", "radio"), it.radioValue = "t" === e.value
        }();
    var wi = /\r/g;
    st.fn.extend({
        "val": function (e) {
            var t, i, n, s = this[0]; {
                if (arguments.length) return n = st.isFunction(e), this.each(function (i) {
                    var s;
                    1 === this.nodeType && (s = n ? e.call(this, i, st(this).val()) : e, null == s ? s = "" : "number" == typeof s ? s += "" : st.isArray(s) && (s = st.map(s, function (e) {
                        return null == e ? "" : e + ""
                    })), t = st.valHooks[this.type] || st.valHooks[this.nodeName.toLowerCase()], t && "set" in t && void 0 !== t.set(this, s, "value") || (this.value = s))
                });
                if (s) return t = st.valHooks[s.type] || st.valHooks[s.nodeName.toLowerCase()], t && "get" in t && void 0 !== (i = t.get(s, "value")) ? i : (i = s.value, "string" == typeof i ? i.replace(wi, "") : null == i ? "" : i)
            }
        }
    }), st.extend({
        "valHooks": {
            "option": {
                "get": function (e) {
                    var t = st.find.attr(e, "value");
                    return null != t ? t : st.trim(st.text(e))
                }
            },
            "select": {
                "get": function (e) {
                    for (var t, i, n = e.options, s = e.selectedIndex, r = "select-one" === e.type || 0 > s, a = r ? null : [], o = r ? s + 1 : n.length, l = 0 > s ? o : r ? s : 0; o > l; l++)
                        if (i = n[l], !(!i.selected && l !== s || (it.optDisabled ? i.disabled : null !== i.getAttribute("disabled")) || i.parentNode.disabled && st.nodeName(i.parentNode, "optgroup"))) {
                            if (t = st(i).val(), r) return t;
                            a.push(t)
                        }
                    return a
                },
                "set": function (e, t) {
                    for (var i, n, s = e.options, r = st.makeArray(t), a = s.length; a--;)
                        if (n = s[a], st.inArray(st.valHooks.option.get(n), r) >= 0) try {
                            n.selected = i = !0
                        } catch (o) {
                            n.scrollHeight
                        } else n.selected = !1;
                    return i || (e.selectedIndex = -1), s
                }
            }
        }
    }), st.each(["radio", "checkbox"], function () {
        st.valHooks[this] = {
            "set": function (e, t) {
                return st.isArray(t) ? e.checked = st.inArray(st(e).val(), t) >= 0 : void 0
            }
        }, it.checkOn || (st.valHooks[this].get = function (e) {
            return null === e.getAttribute("value") ? "on" : e.value
        })
    });
    var xi, ki, _i = st.expr.attrHandle,
        Ci = /^(?:checked|selected)$/i,
        Di = it.getSetAttribute,
        Si = it.input;
    st.fn.extend({
        "attr": function (e, t) {
            return Mt(this, st.attr, e, t, arguments.length > 1)
        },
        "removeAttr": function (e) {
            return this.each(function () {
                st.removeAttr(this, e)
            })
        }
    }), st.extend({
        "attr": function (e, t, i) {
            var n, s, r = e.nodeType;
            if (e && 3 !== r && 8 !== r && 2 !== r) return typeof e.getAttribute === _t ? st.prop(e, t, i) : (1 === r && st.isXMLDoc(e) || (t = t.toLowerCase(), n = st.attrHooks[t] || (st.expr.match.bool.test(t) ? ki : xi)), void 0 === i ? n && "get" in n && null !== (s = n.get(e, t)) ? s : (s = st.find.attr(e, t), null == s ? void 0 : s) : null !== i ? n && "set" in n && void 0 !== (s = n.set(e, i, t)) ? s : (e.setAttribute(t, i + ""), i) : void st.removeAttr(e, t))
        },
        "removeAttr": function (e, t) {
            var i, n, s = 0,
                r = t && t.match(bt);
            if (r && 1 === e.nodeType)
                for (; i = r[s++];) n = st.propFix[i] || i, st.expr.match.bool.test(i) ? Si && Di || !Ci.test(i) ? e[n] = !1 : e[st.camelCase("default-" + i)] = e[n] = !1 : st.attr(e, i, ""), e.removeAttribute(Di ? i : n)
        },
        "attrHooks": {
            "type": {
                "set": function (e, t) {
                    if (!it.radioValue && "radio" === t && st.nodeName(e, "input")) {
                        var i = e.value;
                        return e.setAttribute("type", t), i && (e.value = i), t
                    }
                }
            }
        }
    }), ki = {
        "set": function (e, t, i) {
            return t === !1 ? st.removeAttr(e, i) : Si && Di || !Ci.test(i) ? e.setAttribute(!Di && st.propFix[i] || i, i) : e[st.camelCase("default-" + i)] = e[i] = !0, i
        }
    }, st.each(st.expr.match.bool.source.match(/\w+/g), function (e, t) {
        var i = _i[t] || st.find.attr;
        _i[t] = Si && Di || !Ci.test(t) ? function (e, t, n) {
            var s, r;
            return n || (r = _i[t], _i[t] = s, s = null != i(e, t, n) ? t.toLowerCase() : null, _i[t] = r), s
        } : function (e, t, i) {
            return i ? void 0 : e[st.camelCase("default-" + t)] ? t.toLowerCase() : null
        }
    }), Si && Di || (st.attrHooks.value = {
        "set": function (e, t, i) {
            return st.nodeName(e, "input") ? void(e.defaultValue = t) : xi && xi.set(e, t, i)
        }
    }), Di || (xi = {
        "set": function (e, t, i) {
            var n = e.getAttributeNode(i);
            return n || e.setAttributeNode(n = e.ownerDocument.createAttribute(i)), n.value = t += "", "value" === i || t === e.getAttribute(i) ? t : void 0
        }
    }, _i.id = _i.name = _i.coords = function (e, t, i) {
        var n;
        return i ? void 0 : (n = e.getAttributeNode(t)) && "" !== n.value ? n.value : null
    }, st.valHooks.button = {
        "get": function (e, t) {
            var i = e.getAttributeNode(t);
            return i && i.specified ? i.value : void 0
        },
        "set": xi.set
    }, st.attrHooks.contenteditable = {
        "set": function (e, t, i) {
            xi.set(e, "" === t ? !1 : t, i)
        }
    }, st.each(["width", "height"], function (e, t) {
        st.attrHooks[t] = {
            "set": function (e, i) {
                return "" === i ? (e.setAttribute(t, "auto"), i) : void 0
            }
        }
    })), it.style || (st.attrHooks.style = {
        "get": function (e) {
            return e.style.cssText || void 0
        },
        "set": function (e, t) {
            return e.style.cssText = t + ""
        }
    });
    var Ti = /^(?:input|select|textarea|button|object)$/i,
        Ei = /^(?:a|area)$/i;
    st.fn.extend({
        "prop": function (e, t) {
            return Mt(this, st.prop, e, t, arguments.length > 1)
        },
        "removeProp": function (e) {
            return e = st.propFix[e] || e, this.each(function () {
                try {
                    this[e] = void 0, delete this[e]
                } catch (t) {}
            })
        }
    }), st.extend({
        "propFix": {
            "for": "htmlFor",
            "class": "className"
        },
        "prop": function (e, t, i) {
            var n, s, r, a = e.nodeType;
            if (e && 3 !== a && 8 !== a && 2 !== a) return r = 1 !== a || !st.isXMLDoc(e), r && (t = st.propFix[t] || t, s = st.propHooks[t]), void 0 !== i ? s && "set" in s && void 0 !== (n = s.set(e, i, t)) ? n : e[t] = i : s && "get" in s && null !== (n = s.get(e, t)) ? n : e[t]
        },
        "propHooks": {
            "tabIndex": {
                "get": function (e) {
                    var t = st.find.attr(e, "tabindex");
                    return t ? parseInt(t, 10) : Ti.test(e.nodeName) || Ei.test(e.nodeName) && e.href ? 0 : -1
                }
            }
        }
    }), it.hrefNormalized || st.each(["href", "src"], function (e, t) {
        st.propHooks[t] = {
            "get": function (e) {
                return e.getAttribute(t, 4)
            }
        }
    }), it.optSelected || (st.propHooks.selected = {
        "get": function (e) {
            var t = e.parentNode;
            return t && (t.selectedIndex, t.parentNode && t.parentNode.selectedIndex), null
        }
    }), st.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function () {
        st.propFix[this.toLowerCase()] = this
    }), it.enctype || (st.propFix.enctype = "encoding");
    var Mi = /[\t\r\n\f]/g;
    st.fn.extend({
        "addClass": function (e) {
            var t, i, n, s, r, a, o = 0,
                l = this.length,
                c = "string" == typeof e && e;
            if (st.isFunction(e)) return this.each(function (t) {
                st(this).addClass(e.call(this, t, this.className))
            });
            if (c)
                for (t = (e || "").match(bt) || []; l > o; o++)
                    if (i = this[o], n = 1 === i.nodeType && (i.className ? (" " + i.className + " ").replace(Mi, " ") : " ")) {
                        for (r = 0; s = t[r++];) n.indexOf(" " + s + " ") < 0 && (n += s + " ");
                        a = st.trim(n), i.className !== a && (i.className = a)
                    }
            return this
        },
        "removeClass": function (e) {
            var t, i, n, s, r, a, o = 0,
                l = this.length,
                c = 0 === arguments.length || "string" == typeof e && e;
            if (st.isFunction(e)) return this.each(function (t) {
                st(this).removeClass(e.call(this, t, this.className))
            });
            if (c)
                for (t = (e || "").match(bt) || []; l > o; o++)
                    if (i = this[o], n = 1 === i.nodeType && (i.className ? (" " + i.className + " ").replace(Mi, " ") : "")) {
                        for (r = 0; s = t[r++];)
                            for (; n.indexOf(" " + s + " ") >= 0;) n = n.replace(" " + s + " ", " ");
                        a = e ? st.trim(n) : "", i.className !== a && (i.className = a)
                    }
            return this
        },
        "toggleClass": function (e, t) {
            var i = typeof e;
            return "boolean" == typeof t && "string" === i ? t ? this.addClass(e) : this.removeClass(e) : this.each(st.isFunction(e) ? function (i) {
                st(this).toggleClass(e.call(this, i, this.className, t), t)
            } : function () {
                if ("string" === i)
                    for (var t, n = 0, s = st(this), r = e.match(bt) || []; t = r[n++];) s.hasClass(t) ? s.removeClass(t) : s.addClass(t);
                else(i === _t || "boolean" === i) && (this.className && st._data(this, "__className__", this.className), this.className = this.className || e === !1 ? "" : st._data(this, "__className__") || "")
            })
        },
        "hasClass": function (e) {
            for (var t = " " + e + " ", i = 0, n = this.length; n > i; i++)
                if (1 === this[i].nodeType && (" " + this[i].className + " ").replace(Mi, " ").indexOf(t) >= 0) return !0;
            return !1
        }
    }), st.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function (e, t) {
        st.fn[t] = function (e, i) {
            return arguments.length > 0 ? this.on(t, null, e, i) : this.trigger(t)
        }
    }), st.fn.extend({
        "hover": function (e, t) {
            return this.mouseenter(e).mouseleave(t || e)
        },
        "bind": function (e, t, i) {
            return this.on(e, null, t, i)
        },
        "unbind": function (e, t) {
            return this.off(e, null, t)
        },
        "delegate": function (e, t, i, n) {
            return this.on(t, e, i, n)
        },
        "undelegate": function (e, t, i) {
            return 1 === arguments.length ? this.off(e, "**") : this.off(t, e || "**", i)
        }
    });
    var Ni = st.now(),
        Ai = /\?/,
        Ii = /(,)|(\[|{)|(}|])|"(?:[^"\\\r\n]|\\["\\\/bfnrt]|\\u[\da-fA-F]{4})*"\s*:?|true|false|null|-?(?!0\d)\d+(?:\.\d+|)(?:[eE][+-]?\d+|)/g;
    st.parseJSON = function (t) {
        if (e.JSON && e.JSON.parse) return e.JSON.parse(t + "");
        var i, n = null,
            s = st.trim(t + "");
        return s && !st.trim(s.replace(Ii, function (e, t, s, r) {
            return i && t && (n = 0), 0 === n ? e : (i = s || t, n += !r - !s, "")
        })) ? Function("return " + s)() : st.error("Invalid JSON: " + t)
    }, st.parseXML = function (t) {
        var i, n;
        if (!t || "string" != typeof t) return null;
        try {
            e.DOMParser ? (n = new DOMParser, i = n.parseFromString(t, "text/xml")) : (i = new ActiveXObject("Microsoft.XMLDOM"), i.async = "false", i.loadXML(t))
        } catch (s) {
            i = void 0
        }
        return i && i.documentElement && !i.getElementsByTagName("parsererror").length || st.error("Invalid XML: " + t), i
    };
    var ji, Oi, Fi = /#.*$/,
        Li = /([?&])_=[^&]*/,
        Pi = /^(.*?):[ \t]*([^\r\n]*)\r?$/gm,
        Hi = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/,
        Ri = /^(?:GET|HEAD)$/,
        Wi = /^\/\//,
        qi = /^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/,
        zi = {},
        Bi = {},
        Yi = "*/".concat("*");
    try {
        Oi = location.href
    } catch (Ui) {
        Oi = ft.createElement("a"), Oi.href = "", Oi = Oi.href
    }
    ji = qi.exec(Oi.toLowerCase()) || [], st.extend({
        "active": 0,
        "lastModified": {},
        "etag": {},
        "ajaxSettings": {
            "url": Oi,
            "type": "GET",
            "isLocal": Hi.test(ji[1]),
            "global": !0,
            "processData": !0,
            "async": !0,
            "contentType": "application/x-www-form-urlencoded; charset=UTF-8",
            "accepts": {
                "*": Yi,
                "text": "text/plain",
                "html": "text/html",
                "xml": "application/xml, text/xml",
                "json": "application/json, text/javascript"
            },
            "contents": {
                "xml": /xml/,
                "html": /html/,
                "json": /json/
            },
            "responseFields": {
                "xml": "responseXML",
                "text": "responseText",
                "json": "responseJSON"
            },
            "converters": {
                "* text": String,
                "text html": !0,
                "text json": st.parseJSON,
                "text xml": st.parseXML
            },
            "flatOptions": {
                "url": !0,
                "context": !0
            }
        },
        "ajaxSetup": function (e, t) {
            return t ? q(q(e, st.ajaxSettings), t) : q(st.ajaxSettings, e)
        },
        "ajaxPrefilter": R(zi),
        "ajaxTransport": R(Bi),
        "ajax": function (e, t) {
            function i(e, t, i, n) {
                var s, u, v, y, w, k = t;
                2 !== b && (b = 2, o && clearTimeout(o), c = void 0, a = n || "", x.readyState = e > 0 ? 4 : 0, s = e >= 200 && 300 > e || 304 === e, i && (y = z(h, x, i)), y = B(h, y, x, s), s ? (h.ifModified && (w = x.getResponseHeader("Last-Modified"), w && (st.lastModified[r] = w), w = x.getResponseHeader("etag"), w && (st.etag[r] = w)), 204 === e || "HEAD" === h.type ? k = "nocontent" : 304 === e ? k = "notmodified" : (k = y.state, u = y.data, v = y.error, s = !v)) : (v = k, (e || !k) && (k = "error", 0 > e && (e = 0))), x.status = e, x.statusText = (t || k) + "", s ? f.resolveWith(d, [u, k, x]) : f.rejectWith(d, [x, k, v]), x.statusCode(g), g = void 0, l && p.trigger(s ? "ajaxSuccess" : "ajaxError", [x, h, s ? u : v]), m.fireWith(d, [x, k]), l && (p.trigger("ajaxComplete", [x, h]), --st.active || st.event.trigger("ajaxStop")))
            }
            "object" == typeof e && (t = e, e = void 0), t = t || {};
            var n, s, r, a, o, l, c, u, h = st.ajaxSetup({}, t),
                d = h.context || h,
                p = h.context && (d.nodeType || d.jquery) ? st(d) : st.event,
                f = st.Deferred(),
                m = st.Callbacks("once memory"),
                g = h.statusCode || {},
                v = {},
                y = {},
                b = 0,
                w = "canceled",
                x = {
                    "readyState": 0,
                    "getResponseHeader": function (e) {
                        var t;
                        if (2 === b) {
                            if (!u)
                                for (u = {}; t = Pi.exec(a);) u[t[1].toLowerCase()] = t[2];
                            t = u[e.toLowerCase()]
                        }
                        return null == t ? null : t
                    },
                    "getAllResponseHeaders": function () {
                        return 2 === b ? a : null
                    },
                    "setRequestHeader": function (e, t) {
                        var i = e.toLowerCase();
                        return b || (e = y[i] = y[i] || e, v[e] = t), this
                    },
                    "overrideMimeType": function (e) {
                        return b || (h.mimeType = e), this
                    },
                    "statusCode": function (e) {
                        var t;
                        if (e)
                            if (2 > b)
                                for (t in e) g[t] = [g[t], e[t]];
                            else x.always(e[x.status]);
                        return this
                    },
                    "abort": function (e) {
                        var t = e || w;
                        return c && c.abort(t), i(0, t), this
                    }
                };
            if (f.promise(x).complete = m.add, x.success = x.done, x.error = x.fail, h.url = ((e || h.url || Oi) + "").replace(Fi, "").replace(Wi, ji[1] + "//"), h.type = t.method || t.type || h.method || h.type, h.dataTypes = st.trim(h.dataType || "*").toLowerCase().match(bt) || [""], null == h.crossDomain && (n = qi.exec(h.url.toLowerCase()), h.crossDomain = !(!n || n[1] === ji[1] && n[2] === ji[2] && (n[3] || ("http:" === n[1] ? "80" : "443")) === (ji[3] || ("http:" === ji[1] ? "80" : "443")))), h.data && h.processData && "string" != typeof h.data && (h.data = st.param(h.data, h.traditional)), W(zi, h, t, x), 2 === b) return x;
            l = h.global, l && 0 === st.active++ && st.event.trigger("ajaxStart"), h.type = h.type.toUpperCase(), h.hasContent = !Ri.test(h.type), r = h.url, h.hasContent || (h.data && (r = h.url += (Ai.test(r) ? "&" : "?") + h.data, delete h.data), h.cache === !1 && (h.url = Li.test(r) ? r.replace(Li, "$1_=" + Ni++) : r + (Ai.test(r) ? "&" : "?") + "_=" + Ni++)), h.ifModified && (st.lastModified[r] && x.setRequestHeader("If-Modified-Since", st.lastModified[r]), st.etag[r] && x.setRequestHeader("If-None-Match", st.etag[r])), (h.data && h.hasContent && h.contentType !== !1 || t.contentType) && x.setRequestHeader("Content-Type", h.contentType), x.setRequestHeader("Accept", h.dataTypes[0] && h.accepts[h.dataTypes[0]] ? h.accepts[h.dataTypes[0]] + ("*" !== h.dataTypes[0] ? ", " + Yi + "; q=0.01" : "") : h.accepts["*"]);
            for (s in h.headers) x.setRequestHeader(s, h.headers[s]);
            if (h.beforeSend && (h.beforeSend.call(d, x, h) === !1 || 2 === b)) return x.abort();
            w = "abort";
            for (s in {
                    "success": 1,
                    "error": 1,
                    "complete": 1
                }) x[s](h[s]);
            if (c = W(Bi, h, t, x)) {
                x.readyState = 1, l && p.trigger("ajaxSend", [x, h]), h.async && h.timeout > 0 && (o = setTimeout(function () {
                    x.abort("timeout")
                }, h.timeout));
                try {
                    b = 1, c.send(v, i)
                } catch (k) {
                    if (!(2 > b)) throw k;
                    i(-1, k)
                }
            } else i(-1, "No Transport");
            return x
        },
        "getJSON": function (e, t, i) {
            return st.get(e, t, i, "json")
        },
        "getScript": function (e, t) {
            return st.get(e, void 0, t, "script")
        }
    }), st.each(["get", "post"], function (e, t) {
        st[t] = function (e, i, n, s) {
            return st.isFunction(i) && (s = s || n, n = i, i = void 0), st.ajax({
                "url": e,
                "type": t,
                "dataType": s,
                "data": i,
                "success": n
            })
        }
    }), st.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function (e, t) {
        st.fn[t] = function (e) {
            return this.on(t, e)
        }
    }), st._evalUrl = function (e) {
        return st.ajax({
            "url": e,
            "type": "GET",
            "dataType": "script",
            "async": !1,
            "global": !1,
            "throws": !0
        })
    }, st.fn.extend({
        "wrapAll": function (e) {
            if (st.isFunction(e)) return this.each(function (t) {
                st(this).wrapAll(e.call(this, t))
            });
            if (this[0]) {
                var t = st(e, this[0].ownerDocument).eq(0).clone(!0);
                this[0].parentNode && t.insertBefore(this[0]), t.map(function () {
                    for (var e = this; e.firstChild && 1 === e.firstChild.nodeType;) e = e.firstChild;
                    return e
                }).append(this)
            }
            return this
        },
        "wrapInner": function (e) {
            return this.each(st.isFunction(e) ? function (t) {
                st(this).wrapInner(e.call(this, t))
            } : function () {
                var t = st(this),
                    i = t.contents();
                i.length ? i.wrapAll(e) : t.append(e)
            })
        },
        "wrap": function (e) {
            var t = st.isFunction(e);
            return this.each(function (i) {
                st(this).wrapAll(t ? e.call(this, i) : e)
            })
        },
        "unwrap": function () {
            return this.parent().each(function () {
                st.nodeName(this, "body") || st(this).replaceWith(this.childNodes)
            }).end()
        }
    }), st.expr.filters.hidden = function (e) {
        return e.offsetWidth <= 0 && e.offsetHeight <= 0 || !it.reliableHiddenOffsets() && "none" === (e.style && e.style.display || st.css(e, "display"))
    }, st.expr.filters.visible = function (e) {
        return !st.expr.filters.hidden(e)
    };
    var Ki = /%20/g,
        $i = /\[\]$/,
        Vi = /\r?\n/g,
        Xi = /^(?:submit|button|image|reset|file)$/i,
        Gi = /^(?:input|select|textarea|keygen)/i;
    st.param = function (e, t) {
        var i, n = [],
            s = function (e, t) {
                t = st.isFunction(t) ? t() : null == t ? "" : t, n[n.length] = encodeURIComponent(e) + "=" + encodeURIComponent(t)
            };
        if (void 0 === t && (t = st.ajaxSettings && st.ajaxSettings.traditional), st.isArray(e) || e.jquery && !st.isPlainObject(e)) st.each(e, function () {
            s(this.name, this.value)
        });
        else
            for (i in e) Y(i, e[i], t, s);
        return n.join("&").replace(Ki, "+")
    }, st.fn.extend({
        "serialize": function () {
            return st.param(this.serializeArray())
        },
        "serializeArray": function () {
            return this.map(function () {
                var e = st.prop(this, "elements");
                return e ? st.makeArray(e) : this
            }).filter(function () {
                var e = this.type;
                return this.name && !st(this).is(":disabled") && Gi.test(this.nodeName) && !Xi.test(e) && (this.checked || !Nt.test(e))
            }).map(function (e, t) {
                var i = st(this).val();
                return null == i ? null : st.isArray(i) ? st.map(i, function (e) {
                    return {
                        "name": t.name,
                        "value": e.replace(Vi, "\r\n")
                    }
                }) : {
                    "name": t.name,
                    "value": i.replace(Vi, "\r\n")
                }
            }).get()
        }
    }), st.ajaxSettings.xhr = void 0 !== e.ActiveXObject ? function () {
        return !this.isLocal && /^(get|post|head|put|delete|options)$/i.test(this.type) && U() || K()
    } : U;
    var Ji = 0,
        Qi = {},
        Zi = st.ajaxSettings.xhr();
    e.ActiveXObject && st(e).on("unload", function () {
        for (var e in Qi) Qi[e](void 0, !0)
    }), it.cors = !!Zi && "withCredentials" in Zi, Zi = it.ajax = !!Zi, Zi && st.ajaxTransport(function (e) {
        if (!e.crossDomain || it.cors) {
            var t;
            return {
                "send": function (i, n) {
                    var s, r = e.xhr(),
                        a = ++Ji;
                    if (r.open(e.type, e.url, e.async, e.username, e.password), e.xhrFields)
                        for (s in e.xhrFields) r[s] = e.xhrFields[s];
                    e.mimeType && r.overrideMimeType && r.overrideMimeType(e.mimeType), e.crossDomain || i["X-Requested-With"] || (i["X-Requested-With"] = "XMLHttpRequest");
                    for (s in i) void 0 !== i[s] && r.setRequestHeader(s, i[s] + "");
                    r.send(e.hasContent && e.data || null), t = function (i, s) {
                        var o, l, c;
                        if (t && (s || 4 === r.readyState))
                            if (delete Qi[a], t = void 0, r.onreadystatechange = st.noop, s) 4 !== r.readyState && r.abort();
                            else {
                                c = {}, o = r.status, "string" == typeof r.responseText && (c.text = r.responseText);
                                try {
                                    l = r.statusText
                                } catch (u) {
                                    l = ""
                                }
                                o || !e.isLocal || e.crossDomain ? 1223 === o && (o = 204) : o = c.text ? 200 : 404
                            }
                        c && n(o, l, c, r.getAllResponseHeaders())
                    }, e.async ? 4 === r.readyState ? setTimeout(t) : r.onreadystatechange = Qi[a] = t : t()
                },
                "abort": function () {
                    t && t(void 0, !0)
                }
            }
        }
    }), st.ajaxSetup({
        "accepts": {
            "script": "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
        },
        "contents": {
            "script": /(?:java|ecma)script/
        },
        "converters": {
            "text script": function (e) {
                return st.globalEval(e), e
            }
        }
    }), st.ajaxPrefilter("script", function (e) {
        void 0 === e.cache && (e.cache = !1), e.crossDomain && (e.type = "GET", e.global = !1)
    }), st.ajaxTransport("script", function (e) {
        if (e.crossDomain) {
            var t, i = ft.head || st("head")[0] || ft.documentElement;
            return {
                "send": function (n, s) {
                    t = ft.createElement("script"), t.async = !0, e.scriptCharset && (t.charset = e.scriptCharset), t.src = e.url, t.onload = t.onreadystatechange = function (e, i) {
                        (i || !t.readyState || /loaded|complete/.test(t.readyState)) && (t.onload = t.onreadystatechange = null, t.parentNode && t.parentNode.removeChild(t), t = null, i || s(200, "success"))
                    }, i.insertBefore(t, i.firstChild)
                },
                "abort": function () {
                    t && t.onload(void 0, !0)
                }
            }
        }
    });
    var en = [],
        tn = /(=)\?(?=&|$)|\?\?/;
    st.ajaxSetup({
        "jsonp": "callback",
        "jsonpCallback": function () {
            var e = en.pop() || st.expando + "_" + Ni++;
            return this[e] = !0, e
        }
    }), st.ajaxPrefilter("json jsonp", function (t, i, n) {
        var s, r, a, o = t.jsonp !== !1 && (tn.test(t.url) ? "url" : "string" == typeof t.data && !(t.contentType || "").indexOf("application/x-www-form-urlencoded") && tn.test(t.data) && "data");
        return o || "jsonp" === t.dataTypes[0] ? (s = t.jsonpCallback = st.isFunction(t.jsonpCallback) ? t.jsonpCallback() : t.jsonpCallback, o ? t[o] = t[o].replace(tn, "$1" + s) : t.jsonp !== !1 && (t.url += (Ai.test(t.url) ? "&" : "?") + t.jsonp + "=" + s), t.converters["script json"] = function () {
            return a || st.error(s + " was not called"), a[0]
        }, t.dataTypes[0] = "json", r = e[s], e[s] = function () {
            a = arguments
        }, n.always(function () {
            e[s] = r, t[s] && (t.jsonpCallback = i.jsonpCallback, en.push(s)), a && st.isFunction(r) && r(a[0]), a = r = void 0
        }), "script") : void 0
    }), st.parseHTML = function (e, t, i) {
        if (!e || "string" != typeof e) return null;
        "boolean" == typeof t && (i = t, t = !1), t = t || ft;
        var n = ht.exec(e),
            s = !i && [];
        return n ? [t.createElement(n[1])] : (n = st.buildFragment([e], t, s), s && s.length && st(s).remove(), st.merge([], n.childNodes))
    };
    var nn = st.fn.load;
    st.fn.load = function (e, t, i) {
        if ("string" != typeof e && nn) return nn.apply(this, arguments);
        var n, s, r, a = this,
            o = e.indexOf(" ");
        return o >= 0 && (n = st.trim(e.slice(o, e.length)), e = e.slice(0, o)), st.isFunction(t) ? (i = t, t = void 0) : t && "object" == typeof t && (r = "POST"), a.length > 0 && st.ajax({
            "url": e,
            "type": r,
            "dataType": "html",
            "data": t
        }).done(function (e) {
            s = arguments, a.html(n ? st("<div>").append(st.parseHTML(e)).find(n) : e)
        }).complete(i && function (e, t) {
            a.each(i, s || [e.responseText, t, e])
        }), this
    }, st.expr.filters.animated = function (e) {
        return st.grep(st.timers, function (t) {
            return e === t.elem
        }).length
    };
    var sn = e.document.documentElement;
    st.offset = {
        "setOffset": function (e, t, i) {
            var n, s, r, a, o, l, c, u = st.css(e, "position"),
                h = st(e),
                d = {};
            "static" === u && (e.style.position = "relative"), o = h.offset(), r = st.css(e, "top"), l = st.css(e, "left"), c = ("absolute" === u || "fixed" === u) && st.inArray("auto", [r, l]) > -1, c ? (n = h.position(), a = n.top, s = n.left) : (a = parseFloat(r) || 0, s = parseFloat(l) || 0), st.isFunction(t) && (t = t.call(e, i, o)), null != t.top && (d.top = t.top - o.top + a), null != t.left && (d.left = t.left - o.left + s), "using" in t ? t.using.call(e, d) : h.css(d)
        }
    }, st.fn.extend({
        "offset": function (e) {
            if (arguments.length) return void 0 === e ? this : this.each(function (t) {
                st.offset.setOffset(this, e, t)
            });
            var t, i, n = {
                    "top": 0,
                    "left": 0
                },
                s = this[0],
                r = s && s.ownerDocument;
            if (r) return t = r.documentElement, st.contains(t, s) ? (typeof s.getBoundingClientRect !== _t && (n = s.getBoundingClientRect()), i = $(r), {
                "top": n.top + (i.pageYOffset || t.scrollTop) - (t.clientTop || 0),
                "left": n.left + (i.pageXOffset || t.scrollLeft) - (t.clientLeft || 0)
            }) : n
        },
        "position": function () {
            if (this[0]) {
                var e, t, i = {
                        "top": 0,
                        "left": 0
                    },
                    n = this[0];
                return "fixed" === st.css(n, "position") ? t = n.getBoundingClientRect() : (e = this.offsetParent(), t = this.offset(), st.nodeName(e[0], "html") || (i = e.offset()), i.top += st.css(e[0], "borderTopWidth", !0), i.left += st.css(e[0], "borderLeftWidth", !0)), {
                    "top": t.top - i.top - st.css(n, "marginTop", !0),
                    "left": t.left - i.left - st.css(n, "marginLeft", !0)
                }
            }
        },
        "offsetParent": function () {
            return this.map(function () {
                for (var e = this.offsetParent || sn; e && !st.nodeName(e, "html") && "static" === st.css(e, "position");) e = e.offsetParent;
                return e || sn
            })
        }
    }), st.each({
        "scrollLeft": "pageXOffset",
        "scrollTop": "pageYOffset"
    }, function (e, t) {
        var i = /Y/.test(t);
        st.fn[e] = function (n) {
            return Mt(this, function (e, n, s) {
                var r = $(e);
                return void 0 === s ? r ? t in r ? r[t] : r.document.documentElement[n] : e[n] : void(r ? r.scrollTo(i ? st(r).scrollLeft() : s, i ? s : st(r).scrollTop()) : e[n] = s)
            }, e, n, arguments.length, null)
        }
    }), st.each(["top", "left"], function (e, t) {
        st.cssHooks[t] = S(it.pixelPosition, function (e, i) {
            return i ? (i = ti(e, t), ni.test(i) ? st(e).position()[t] + "px" : i) : void 0
        })
    }), st.each({
        "Height": "height",
        "Width": "width"
    }, function (e, t) {
        st.each({
            "padding": "inner" + e,
            "content": t,
            "": "outer" + e
        }, function (i, n) {
            st.fn[n] = function (n, s) {
                var r = arguments.length && (i || "boolean" != typeof n),
                    a = i || (n === !0 || s === !0 ? "margin" : "border");
                return Mt(this, function (t, i, n) {
                    var s;
                    return st.isWindow(t) ? t.document.documentElement["client" + e] : 9 === t.nodeType ? (s = t.documentElement, Math.max(t.body["scroll" + e], s["scroll" + e], t.body["offset" + e], s["offset" + e], s["client" + e])) : void 0 === n ? st.css(t, i, a) : st.style(t, i, n, a)
                }, t, r ? n : void 0, r, null)
            }
        })
    }), st.fn.size = function () {
        return this.length
    }, st.fn.andSelf = st.fn.addBack, "function" == typeof define && define.amd && define("jquery", [], function () {
        return st
    });
    var rn = e.jQuery,
        an = e.$;
    return st.noConflict = function (t) {
        return e.$ === st && (e.$ = an), t && e.jQuery === st && (e.jQuery = rn), st
    }, typeof t === _t && (e.jQuery = e.$ = st), st
}),
function (e, t) {
    e.rails !== t && e.error("jquery-ujs has already been loaded!");
    var i, n = e(document);
    e.rails = i = {
        "linkClickSelector": "a[data-confirm], a[data-method], a[data-remote], a[data-disable-with], a[data-disable]",
        "buttonClickSelector": "button[data-remote]:not(form button), button[data-confirm]:not(form button)",
        "inputChangeSelector": "select[data-remote], input[data-remote], textarea[data-remote]",
        "formSubmitSelector": "form",
        "formInputClickSelector": "form input[type=submit], form input[type=image], form button[type=submit], form button:not([type]), input[type=submit][form], input[type=image][form], button[type=submit][form], button[form]:not([type])",
        "disableSelector": "input[data-disable-with]:enabled, button[data-disable-with]:enabled, textarea[data-disable-with]:enabled, input[data-disable]:enabled, button[data-disable]:enabled, textarea[data-disable]:enabled",
        "enableSelector": "input[data-disable-with]:disabled, button[data-disable-with]:disabled, textarea[data-disable-with]:disabled, input[data-disable]:disabled, button[data-disable]:disabled, textarea[data-disable]:disabled",
        "requiredInputSelector": "input[name][required]:not([disabled]),textarea[name][required]:not([disabled])",
        "fileInputSelector": "input[type=file]",
        "linkDisableSelector": "a[data-disable-with], a[data-disable]",
        "buttonDisableSelector": "button[data-remote][data-disable-with], button[data-remote][data-disable]",
        "CSRFProtection": function (t) {
            var i = e('meta[name="csrf-token"]').attr("content");
            i && t.setRequestHeader("X-CSRF-Token", i)
        },
        "refreshCSRFTokens": function () {
            var t = e("meta[name=csrf-token]").attr("content"),
                i = e("meta[name=csrf-param]").attr("content");
            e('form input[name="' + i + '"]').val(t)
        },
        "fire": function (t, i, n) {
            var s = e.Event(i);
            return t.trigger(s, n), s.result !== !1
        },
        "confirm": function (e) {
            return confirm(e)
        },
        "ajax": function (t) {
            return e.ajax(t)
        },
        "href": function (e) {
            return e[0].href
        },
        "handleRemote": function (n) {
            var s, r, a, o, l, c;
            if (i.fire(n, "ajax:before")) {
                if (o = n.data("with-credentials") || null, l = n.data("type") || e.ajaxSettings && e.ajaxSettings.dataType, n.is("form")) {
                    s = n.attr("method"), r = n.attr("action"), a = n.serializeArray();
                    var u = n.data("ujs:submit-button");
                    u && (a.push(u), n.data("ujs:submit-button", null))
                } else n.is(i.inputChangeSelector) ? (s = n.data("method"), r = n.data("url"), a = n.serialize(), n.data("params") && (a = a + "&" + n.data("params"))) : n.is(i.buttonClickSelector) ? (s = n.data("method") || "get", r = n.data("url"), a = n.serialize(), n.data("params") && (a = a + "&" + n.data("params"))) : (s = n.data("method"), r = i.href(n), a = n.data("params") || null);
                return c = {
                    "type": s || "GET",
                    "data": a,
                    "dataType": l,
                    "beforeSend": function (e, s) {
                        return s.dataType === t && e.setRequestHeader("accept", "*/*;q=0.5, " + s.accepts.script), i.fire(n, "ajax:beforeSend", [e, s]) ? void n.trigger("ajax:send", e) : !1
                    },
                    "success": function (e, t, i) {
                        n.trigger("ajax:success", [e, t, i])
                    },
                    "complete": function (e, t) {
                        n.trigger("ajax:complete", [e, t])
                    },
                    "error": function (e, t, i) {
                        n.trigger("ajax:error", [e, t, i])
                    },
                    "crossDomain": i.isCrossDomain(r)
                }, o && (c.xhrFields = {
                    "withCredentials": o
                }), r && (c.url = r), i.ajax(c)
            }
            return !1
        },
        "isCrossDomain": function (e) {
            var t = document.createElement("a");
            t.href = location.href;
            var i = document.createElement("a");
            try {
                return i.href = e, i.href = i.href, !i.protocol || !i.host || t.protocol + "//" + t.host != i.protocol + "//" + i.host
            } catch (n) {
                return !0
            }
        },
        "handleMethod": function (n) {
            var s = i.href(n),
                r = n.data("method"),
                a = n.attr("target"),
                o = e("meta[name=csrf-token]").attr("content"),
                l = e("meta[name=csrf-param]").attr("content"),
                c = e('<form method="post" action="' + s + '"></form>'),
                u = '<input name="_method" value="' + r + '" type="hidden" />';
            l === t || o === t || i.isCrossDomain(s) || (u += '<input name="' + l + '" value="' + o + '" type="hidden" />'), a && c.attr("target", a), c.hide().append(u).appendTo("body"), c.submit()
        },
        "formElements": function (t, i) {
            return t.is("form") ? e(t[0].elements).filter(i) : t.find(i)
        },
        "disableFormElements": function (t) {
            i.formElements(t, i.disableSelector).each(function () {
                i.disableFormElement(e(this))
            })
        },
        "disableFormElement": function (e) {
            var i, n;
            i = e.is("button") ? "html" : "val", n = e.data("disable-with"), e.data("ujs:enable-with", e[i]()), n !== t && e[i](n), e.prop("disabled", !0)
        },
        "enableFormElements": function (t) {
            i.formElements(t, i.enableSelector).each(function () {
                i.enableFormElement(e(this))
            })
        },
        "enableFormElement": function (e) {
            var t = e.is("button") ? "html" : "val";
            e.data("ujs:enable-with") && e[t](e.data("ujs:enable-with")), e.prop("disabled", !1)
        },
        "allowAction": function (e) {
            var t, n = e.data("confirm"),
                s = !1;
            return n ? (i.fire(e, "confirm") && (s = i.confirm(n), t = i.fire(e, "confirm:complete", [s])), s && t) : !0
        },
        "blankInputs": function (t, i, n) {
            var s, r, a = e(),
                o = i || "input,textarea",
                l = t.find(o);
            return l.each(function () {
                if (s = e(this), r = s.is("input[type=checkbox],input[type=radio]") ? s.is(":checked") : s.val(), !r == !n) {
                    if (s.is("input[type=radio]") && l.filter('input[type=radio]:checked[name="' + s.attr("name") + '"]').length) return !0;
                    a = a.add(s)
                }
            }), a.length ? a : !1
        },
        "nonBlankInputs": function (e, t) {
            return i.blankInputs(e, t, !0)
        },
        "stopEverything": function (t) {
            return e(t.target).trigger("ujs:everythingStopped"), t.stopImmediatePropagation(), !1
        },
        "disableElement": function (e) {
            var n = e.data("disable-with");
            e.data("ujs:enable-with", e.html()), n !== t && e.html(n), e.bind("click.railsDisable", function (e) {
                return i.stopEverything(e)
            })
        },
        "enableElement": function (e) {
            e.data("ujs:enable-with") !== t && (e.html(e.data("ujs:enable-with")), e.removeData("ujs:enable-with")), e.unbind("click.railsDisable")
        }
    }, i.fire(n, "rails:attachBindings") && (e.ajaxPrefilter(function (e, t, n) {
        e.crossDomain || i.CSRFProtection(n)
    }), n.delegate(i.linkDisableSelector, "ajax:complete", function () {
        i.enableElement(e(this))
    }), n.delegate(i.buttonDisableSelector, "ajax:complete", function () {
        i.enableFormElement(e(this))
    }), n.delegate(i.linkClickSelector, "click.rails", function (n) {
        var s = e(this),
            r = s.data("method"),
            a = s.data("params"),
            o = n.metaKey || n.ctrlKey;
        if (!i.allowAction(s)) return i.stopEverything(n);
        if (!o && s.is(i.linkDisableSelector) && i.disableElement(s), s.data("remote") !== t) {
            if (o && (!r || "GET" === r) && !a) return !0;
            var l = i.handleRemote(s);
            return l === !1 ? i.enableElement(s) : l.error(function () {
                i.enableElement(s)
            }), !1
        }
        return s.data("method") ? (i.handleMethod(s), !1) : void 0
    }), n.delegate(i.buttonClickSelector, "click.rails", function (t) {
        var n = e(this);
        if (!i.allowAction(n)) return i.stopEverything(t);
        n.is(i.buttonDisableSelector) && i.disableFormElement(n);
        var s = i.handleRemote(n);
        return s === !1 ? i.enableFormElement(n) : s.error(function () {
            i.enableFormElement(n)
        }), !1
    }), n.delegate(i.inputChangeSelector, "change.rails", function (t) {
        var n = e(this);
        return i.allowAction(n) ? (i.handleRemote(n), !1) : i.stopEverything(t)
    }), n.delegate(i.formSubmitSelector, "submit.rails", function (n) {
        var s, r, a = e(this),
            o = a.data("remote") !== t;
        if (!i.allowAction(a)) return i.stopEverything(n);
        if (a.attr("novalidate") == t && (s = i.blankInputs(a, i.requiredInputSelector), s && i.fire(a, "ajax:aborted:required", [s]))) return i.stopEverything(n);
        if (o) {
            if (r = i.nonBlankInputs(a, i.fileInputSelector)) {
                setTimeout(function () {
                    i.disableFormElements(a)
                }, 13);
                var l = i.fire(a, "ajax:aborted:file", [r]);
                return l || setTimeout(function () {
                    i.enableFormElements(a)
                }, 13), l
            }
            return i.handleRemote(a), !1
        }
        setTimeout(function () {
            i.disableFormElements(a)
        }, 13)
    }), n.delegate(i.formInputClickSelector, "click.rails", function (t) {
        var n = e(this);
        if (!i.allowAction(n)) return i.stopEverything(t);
        var s = n.attr("name"),
            r = s ? {
                "name": s,
                "value": n.val()
            } : null;
        n.closest("form").data("ujs:submit-button", r)
    }), n.delegate(i.formSubmitSelector, "ajax:send.rails", function (t) {
        this == t.target && i.disableFormElements(e(this))
    }), n.delegate(i.formSubmitSelector, "ajax:complete.rails", function (t) {
        this == t.target && i.enableFormElements(e(this))
    }), e(function () {
        i.refreshCSRFTokens()
    }))
}(jQuery),
/*!
 * jQuery UI Core 1.11.4
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/category/ui-core/
 */
function (e) {
    "function" == typeof define && define.amd ? define(["jquery"], e) : e(jQuery)
}(function (e) {
    function t(t, n) {
        var s, r, a, o = t.nodeName.toLowerCase();
        return "area" === o ? (s = t.parentNode, r = s.name, t.href && r && "map" === s.nodeName.toLowerCase() ? (a = e("img[usemap='#" + r + "']")[0], !!a && i(a)) : !1) : (/^(input|select|textarea|button|object)$/.test(o) ? !t.disabled : "a" === o ? t.href || n : n) && i(t)
    }

    function i(t) {
        return e.expr.filters.visible(t) && !e(t).parents().addBack().filter(function () {
            return "hidden" === e.css(this, "visibility")
        }).length
    }
    e.ui = e.ui || {}, e.extend(e.ui, {
        "version": "1.11.4",
        "keyCode": {
            "BACKSPACE": 8,
            "COMMA": 188,
            "DELETE": 46,
            "DOWN": 40,
            "END": 35,
            "ENTER": 13,
            "ESCAPE": 27,
            "HOME": 36,
            "LEFT": 37,
            "PAGE_DOWN": 34,
            "PAGE_UP": 33,
            "PERIOD": 190,
            "RIGHT": 39,
            "SPACE": 32,
            "TAB": 9,
            "UP": 38
        }
    }), e.fn.extend({
        "scrollParent": function (t) {
            var i = this.css("position"),
                n = "absolute" === i,
                s = t ? /(auto|scroll|hidden)/ : /(auto|scroll)/,
                r = this.parents().filter(function () {
                    var t = e(this);
                    return n && "static" === t.css("position") ? !1 : s.test(t.css("overflow") + t.css("overflow-y") + t.css("overflow-x"))
                }).eq(0);
            return "fixed" !== i && r.length ? r : e(this[0].ownerDocument || document)
        },
        "uniqueId": function () {
            var e = 0;
            return function () {
                return this.each(function () {
                    this.id || (this.id = "ui-id-" + ++e)
                })
            }
        }(),
        "removeUniqueId": function () {
            return this.each(function () {
                /^ui-id-\d+$/.test(this.id) && e(this).removeAttr("id")
            })
        }
    }), e.extend(e.expr[":"], {
        "data": e.expr.createPseudo ? e.expr.createPseudo(function (t) {
            return function (i) {
                return !!e.data(i, t)
            }
        }) : function (t, i, n) {
            return !!e.data(t, n[3])
        },
        "focusable": function (i) {
            return t(i, !isNaN(e.attr(i, "tabindex")))
        },
        "tabbable": function (i) {
            var n = e.attr(i, "tabindex"),
                s = isNaN(n);
            return (s || n >= 0) && t(i, !s)
        }
    }), e("<a>").outerWidth(1).jquery || e.each(["Width", "Height"], function (t, i) {
        function n(t, i, n, r) {
            return e.each(s, function () {
                i -= parseFloat(e.css(t, "padding" + this)) || 0, n && (i -= parseFloat(e.css(t, "border" + this + "Width")) || 0), r && (i -= parseFloat(e.css(t, "margin" + this)) || 0)
            }), i
        }
        var s = "Width" === i ? ["Left", "Right"] : ["Top", "Bottom"],
            r = i.toLowerCase(),
            a = {
                "innerWidth": e.fn.innerWidth,
                "innerHeight": e.fn.innerHeight,
                "outerWidth": e.fn.outerWidth,
                "outerHeight": e.fn.outerHeight
            };
        e.fn["inner" + i] = function (t) {
            return void 0 === t ? a["inner" + i].call(this) : this.each(function () {
                e(this).css(r, n(this, t) + "px")
            })
        }, e.fn["outer" + i] = function (t, s) {
            return "number" != typeof t ? a["outer" + i].call(this, t) : this.each(function () {
                e(this).css(r, n(this, t, !0, s) + "px")
            })
        }
    }), e.fn.addBack || (e.fn.addBack = function (e) {
        return this.add(null == e ? this.prevObject : this.prevObject.filter(e))
    }), e("<a>").data("a-b", "a").removeData("a-b").data("a-b") && (e.fn.removeData = function (t) {
        return function (i) {
            return arguments.length ? t.call(this, e.camelCase(i)) : t.call(this)
        }
    }(e.fn.removeData)), e.ui.ie = !!/msie [\w.]+/.exec(navigator.userAgent.toLowerCase()), e.fn.extend({
        "focus": function (t) {
            return function (i, n) {
                return "number" == typeof i ? this.each(function () {
                    var t = this;
                    setTimeout(function () {
                        e(t).focus(), n && n.call(t)
                    }, i)
                }) : t.apply(this, arguments)
            }
        }(e.fn.focus),
        "disableSelection": function () {
            var e = "onselectstart" in document.createElement("div") ? "selectstart" : "mousedown";
            return function () {
                return this.bind(e + ".ui-disableSelection", function (e) {
                    e.preventDefault()
                })
            }
        }(),
        "enableSelection": function () {
            return this.unbind(".ui-disableSelection")
        },
        "zIndex": function (t) {
            if (void 0 !== t) return this.css("zIndex", t);
            if (this.length)
                for (var i, n, s = e(this[0]); s.length && s[0] !== document;) {
                    if (i = s.css("position"), ("absolute" === i || "relative" === i || "fixed" === i) && (n = parseInt(s.css("zIndex"), 10), !isNaN(n) && 0 !== n)) return n;
                    s = s.parent()
                }
            return 0
        }
    }), e.ui.plugin = {
        "add": function (t, i, n) {
            var s, r = e.ui[t].prototype;
            for (s in n) r.plugins[s] = r.plugins[s] || [], r.plugins[s].push([i, n[s]])
        },
        "call": function (e, t, i, n) {
            var s, r = e.plugins[t];
            if (r && (n || e.element[0].parentNode && 11 !== e.element[0].parentNode.nodeType))
                for (s = 0; s < r.length; s++) e.options[r[s][0]] && r[s][1].apply(e.element, i)
        }
    }
}),
/*!
 * jQuery UI Widget 1.11.4
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/jQuery.widget/
 */
function (e) {
    "function" == typeof define && define.amd ? define(["jquery"], e) : e(jQuery)
}(function (e) {
    var t = 0,
        i = Array.prototype.slice;
    return e.cleanData = function (t) {
        return function (i) {
            var n, s, r;
            for (r = 0; null != (s = i[r]); r++) try {
                n = e._data(s, "events"), n && n.remove && e(s).triggerHandler("remove")
            } catch (a) {}
            t(i)
        }
    }(e.cleanData), e.widget = function (t, i, n) {
        var s, r, a, o, l = {},
            c = t.split(".")[0];
        return t = t.split(".")[1], s = c + "-" + t, n || (n = i, i = e.Widget), e.expr[":"][s.toLowerCase()] = function (t) {
            return !!e.data(t, s)
        }, e[c] = e[c] || {}, r = e[c][t], a = e[c][t] = function (e, t) {
            return this._createWidget ? void(arguments.length && this._createWidget(e, t)) : new a(e, t)
        }, e.extend(a, r, {
            "version": n.version,
            "_proto": e.extend({}, n),
            "_childConstructors": []
        }), o = new i, o.options = e.widget.extend({}, o.options), e.each(n, function (t, n) {
            return e.isFunction(n) ? void(l[t] = function () {
                var e = function () {
                        return i.prototype[t].apply(this, arguments)
                    },
                    s = function (e) {
                        return i.prototype[t].apply(this, e)
                    };
                return function () {
                    var t, i = this._super,
                        r = this._superApply;
                    return this._super = e, this._superApply = s, t = n.apply(this, arguments), this._super = i, this._superApply = r, t
                }
            }()) : void(l[t] = n)
        }), a.prototype = e.widget.extend(o, {
            "widgetEventPrefix": r ? o.widgetEventPrefix || t : t
        }, l, {
            "constructor": a,
            "namespace": c,
            "widgetName": t,
            "widgetFullName": s
        }), r ? (e.each(r._childConstructors, function (t, i) {
            var n = i.prototype;
            e.widget(n.namespace + "." + n.widgetName, a, i._proto)
        }), delete r._childConstructors) : i._childConstructors.push(a), e.widget.bridge(t, a), a
    }, e.widget.extend = function (t) {
        for (var n, s, r = i.call(arguments, 1), a = 0, o = r.length; o > a; a++)
            for (n in r[a]) s = r[a][n], r[a].hasOwnProperty(n) && void 0 !== s && (t[n] = e.isPlainObject(s) ? e.isPlainObject(t[n]) ? e.widget.extend({}, t[n], s) : e.widget.extend({}, s) : s);
        return t
    }, e.widget.bridge = function (t, n) {
        var s = n.prototype.widgetFullName || t;
        e.fn[t] = function (r) {
            var a = "string" == typeof r,
                o = i.call(arguments, 1),
                l = this;
            return a ? this.each(function () {
                var i, n = e.data(this, s);
                return "instance" === r ? (l = n, !1) : n ? e.isFunction(n[r]) && "_" !== r.charAt(0) ? (i = n[r].apply(n, o), i !== n && void 0 !== i ? (l = i && i.jquery ? l.pushStack(i.get()) : i, !1) : void 0) : e.error("no such method '" + r + "' for " + t + " widget instance") : e.error("cannot call methods on " + t + " prior to initialization; attempted to call method '" + r + "'")
            }) : (o.length && (r = e.widget.extend.apply(null, [r].concat(o))), this.each(function () {
                var t = e.data(this, s);
                t ? (t.option(r || {}), t._init && t._init()) : e.data(this, s, new n(r, this))
            })), l
        }
    }, e.Widget = function () {}, e.Widget._childConstructors = [], e.Widget.prototype = {
        "widgetName": "widget",
        "widgetEventPrefix": "",
        "defaultElement": "<div>",
        "options": {
            "disabled": !1,
            "create": null
        },
        "_createWidget": function (i, n) {
            n = e(n || this.defaultElement || this)[0], this.element = e(n), this.uuid = t++, this.eventNamespace = "." + this.widgetName + this.uuid, this.bindings = e(), this.hoverable = e(), this.focusable = e(), n !== this && (e.data(n, this.widgetFullName, this), this._on(!0, this.element, {
                "remove": function (e) {
                    e.target === n && this.destroy()
                }
            }), this.document = e(n.style ? n.ownerDocument : n.document || n), this.window = e(this.document[0].defaultView || this.document[0].parentWindow)), this.options = e.widget.extend({}, this.options, this._getCreateOptions(), i), this._create(), this._trigger("create", null, this._getCreateEventData()), this._init()
        },
        "_getCreateOptions": e.noop,
        "_getCreateEventData": e.noop,
        "_create": e.noop,
        "_init": e.noop,
        "destroy": function () {
            this._destroy(), this.element.unbind(this.eventNamespace).removeData(this.widgetFullName).removeData(e.camelCase(this.widgetFullName)), this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName + "-disabled ui-state-disabled"), this.bindings.unbind(this.eventNamespace), this.hoverable.removeClass("ui-state-hover"), this.focusable.removeClass("ui-state-focus")
        },
        "_destroy": e.noop,
        "widget": function () {
            return this.element
        },
        "option": function (t, i) {
            var n, s, r, a = t;
            if (0 === arguments.length) return e.widget.extend({}, this.options);
            if ("string" == typeof t)
                if (a = {}, n = t.split("."), t = n.shift(), n.length) {
                    for (s = a[t] = e.widget.extend({}, this.options[t]), r = 0; r < n.length - 1; r++) s[n[r]] = s[n[r]] || {}, s = s[n[r]];
                    if (t = n.pop(), 1 === arguments.length) return void 0 === s[t] ? null : s[t];
                    s[t] = i
                } else {
                    if (1 === arguments.length) return void 0 === this.options[t] ? null : this.options[t];
                    a[t] = i
                }
            return this._setOptions(a), this
        },
        "_setOptions": function (e) {
            var t;
            for (t in e) this._setOption(t, e[t]);
            return this
        },
        "_setOption": function (e, t) {
            return this.options[e] = t, "disabled" === e && (this.widget().toggleClass(this.widgetFullName + "-disabled", !!t), t && (this.hoverable.removeClass("ui-state-hover"), this.focusable.removeClass("ui-state-focus"))), this
        },
        "enable": function () {
            return this._setOptions({
                "disabled": !1
            })
        },
        "disable": function () {
            return this._setOptions({
                "disabled": !0
            })
        },
        "_on": function (t, i, n) {
            var s, r = this;
            "boolean" != typeof t && (n = i, i = t, t = !1), n ? (i = s = e(i), this.bindings = this.bindings.add(i)) : (n = i, i = this.element, s = this.widget()), e.each(n, function (n, a) {
                function o() {
                    return t || r.options.disabled !== !0 && !e(this).hasClass("ui-state-disabled") ? ("string" == typeof a ? r[a] : a).apply(r, arguments) : void 0
                }
                "string" != typeof a && (o.guid = a.guid = a.guid || o.guid || e.guid++);
                var l = n.match(/^([\w:-]*)\s*(.*)$/),
                    c = l[1] + r.eventNamespace,
                    u = l[2];
                u ? s.delegate(u, c, o) : i.bind(c, o)
            })
        },
        "_off": function (t, i) {
            i = (i || "").split(" ").join(this.eventNamespace + " ") + this.eventNamespace, t.unbind(i).undelegate(i), this.bindings = e(this.bindings.not(t).get()), this.focusable = e(this.focusable.not(t).get()), this.hoverable = e(this.hoverable.not(t).get())
        },
        "_delay": function (e, t) {
            function i() {
                return ("string" == typeof e ? n[e] : e).apply(n, arguments)
            }
            var n = this;
            return setTimeout(i, t || 0)
        },
        "_hoverable": function (t) {
            this.hoverable = this.hoverable.add(t), this._on(t, {
                "mouseenter": function (t) {
                    e(t.currentTarget).addClass("ui-state-hover")
                },
                "mouseleave": function (t) {
                    e(t.currentTarget).removeClass("ui-state-hover")
                }
            })
        },
        "_focusable": function (t) {
            this.focusable = this.focusable.add(t), this._on(t, {
                "focusin": function (t) {
                    e(t.currentTarget).addClass("ui-state-focus")
                },
                "focusout": function (t) {
                    e(t.currentTarget).removeClass("ui-state-focus")
                }
            })
        },
        "_trigger": function (t, i, n) {
            var s, r, a = this.options[t];
            if (n = n || {}, i = e.Event(i), i.type = (t === this.widgetEventPrefix ? t : this.widgetEventPrefix + t).toLowerCase(), i.target = this.element[0], r = i.originalEvent)
                for (s in r) s in i || (i[s] = r[s]);
            return this.element.trigger(i, n), !(e.isFunction(a) && a.apply(this.element[0], [i].concat(n)) === !1 || i.isDefaultPrevented())
        }
    }, e.each({
        "show": "fadeIn",
        "hide": "fadeOut"
    }, function (t, i) {
        e.Widget.prototype["_" + t] = function (n, s, r) {
            "string" == typeof s && (s = {
                "effect": s
            });
            var a, o = s ? s === !0 || "number" == typeof s ? i : s.effect || i : t;
            s = s || {}, "number" == typeof s && (s = {
                "duration": s
            }), a = !e.isEmptyObject(s), s.complete = r, s.delay && n.delay(s.delay), a && e.effects && e.effects.effect[o] ? n[t](s) : o !== t && n[o] ? n[o](s.duration, s.easing, r) : n.queue(function (i) {
                e(this)[t](), r && r.call(n[0]), i()
            })
        }
    }), e.widget
}),
/*!
 * jQuery UI Position 1.11.4
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/position/
 */
function (e) {
    "function" == typeof define && define.amd ? define(["jquery"], e) : e(jQuery)
}(function (e) {
    return function () {
        function t(e, t, i) {
            return [parseFloat(e[0]) * (p.test(e[0]) ? t / 100 : 1), parseFloat(e[1]) * (p.test(e[1]) ? i / 100 : 1)]
        }

        function i(t, i) {
            return parseInt(e.css(t, i), 10) || 0
        }

        function n(t) {
            var i = t[0];
            return 9 === i.nodeType ? {
                "width": t.width(),
                "height": t.height(),
                "offset": {
                    "top": 0,
                    "left": 0
                }
            } : e.isWindow(i) ? {
                "width": t.width(),
                "height": t.height(),
                "offset": {
                    "top": t.scrollTop(),
                    "left": t.scrollLeft()
                }
            } : i.preventDefault ? {
                "width": 0,
                "height": 0,
                "offset": {
                    "top": i.pageY,
                    "left": i.pageX
                }
            } : {
                "width": t.outerWidth(),
                "height": t.outerHeight(),
                "offset": t.offset()
            }
        }
        e.ui = e.ui || {};
        var s, r, a = Math.max,
            o = Math.abs,
            l = Math.round,
            c = /left|center|right/,
            u = /top|center|bottom/,
            h = /[\+\-]\d+(\.[\d]+)?%?/,
            d = /^\w+/,
            p = /%$/,
            f = e.fn.position;
        e.position = {
                "scrollbarWidth": function () {
                    if (void 0 !== s) return s;
                    var t, i, n = e("<div style='display:block;position:absolute;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"),
                        r = n.children()[0];
                    return e("body").append(n), t = r.offsetWidth, n.css("overflow", "scroll"), i = r.offsetWidth, t === i && (i = n[0].clientWidth), n.remove(), s = t - i
                },
                "getScrollInfo": function (t) {
                    var i = t.isWindow || t.isDocument ? "" : t.element.css("overflow-x"),
                        n = t.isWindow || t.isDocument ? "" : t.element.css("overflow-y"),
                        s = "scroll" === i || "auto" === i && t.width < t.element[0].scrollWidth,
                        r = "scroll" === n || "auto" === n && t.height < t.element[0].scrollHeight;
                    return {
                        "width": r ? e.position.scrollbarWidth() : 0,
                        "height": s ? e.position.scrollbarWidth() : 0
                    }
                },
                "getWithinInfo": function (t) {
                    var i = e(t || window),
                        n = e.isWindow(i[0]),
                        s = !!i[0] && 9 === i[0].nodeType;
                    return {
                        "element": i,
                        "isWindow": n,
                        "isDocument": s,
                        "offset": i.offset() || {
                            "left": 0,
                            "top": 0
                        },
                        "scrollLeft": i.scrollLeft(),
                        "scrollTop": i.scrollTop(),
                        "width": n || s ? i.width() : i.outerWidth(),
                        "height": n || s ? i.height() : i.outerHeight()
                    }
                }
            }, e.fn.position = function (s) {
                if (!s || !s.of) return f.apply(this, arguments);
                s = e.extend({}, s);
                var p, m, g, v, y, b, w = e(s.of),
                    x = e.position.getWithinInfo(s.within),
                    k = e.position.getScrollInfo(x),
                    _ = (s.collision || "flip").split(" "),
                    C = {};
                return b = n(w), w[0].preventDefault && (s.at = "left top"), m = b.width, g = b.height, v = b.offset, y = e.extend({}, v), e.each(["my", "at"], function () {
                    var e, t, i = (s[this] || "").split(" ");
                    1 === i.length && (i = c.test(i[0]) ? i.concat(["center"]) : u.test(i[0]) ? ["center"].concat(i) : ["center", "center"]), i[0] = c.test(i[0]) ? i[0] : "center", i[1] = u.test(i[1]) ? i[1] : "center", e = h.exec(i[0]), t = h.exec(i[1]), C[this] = [e ? e[0] : 0, t ? t[0] : 0], s[this] = [d.exec(i[0])[0], d.exec(i[1])[0]]
                }), 1 === _.length && (_[1] = _[0]), "right" === s.at[0] ? y.left += m : "center" === s.at[0] && (y.left += m / 2), "bottom" === s.at[1] ? y.top += g : "center" === s.at[1] && (y.top += g / 2), p = t(C.at, m, g), y.left += p[0], y.top += p[1], this.each(function () {
                    var n, c, u = e(this),
                        h = u.outerWidth(),
                        d = u.outerHeight(),
                        f = i(this, "marginLeft"),
                        b = i(this, "marginTop"),
                        D = h + f + i(this, "marginRight") + k.width,
                        S = d + b + i(this, "marginBottom") + k.height,
                        T = e.extend({}, y),
                        E = t(C.my, u.outerWidth(), u.outerHeight());
                    "right" === s.my[0] ? T.left -= h : "center" === s.my[0] && (T.left -= h / 2), "bottom" === s.my[1] ? T.top -= d : "center" === s.my[1] && (T.top -= d / 2), T.left += E[0], T.top += E[1], r || (T.left = l(T.left), T.top = l(T.top)), n = {
                        "marginLeft": f,
                        "marginTop": b
                    }, e.each(["left", "top"], function (t, i) {
                        e.ui.position[_[t]] && e.ui.position[_[t]][i](T, {
                            "targetWidth": m,
                            "targetHeight": g,
                            "elemWidth": h,
                            "elemHeight": d,
                            "collisionPosition": n,
                            "collisionWidth": D,
                            "collisionHeight": S,
                            "offset": [p[0] + E[0], p[1] + E[1]],
                            "my": s.my,
                            "at": s.at,
                            "within": x,
                            "elem": u
                        })
                    }), s.using && (c = function (e) {
                        var t = v.left - T.left,
                            i = t + m - h,
                            n = v.top - T.top,
                            r = n + g - d,
                            l = {
                                "target": {
                                    "element": w,
                                    "left": v.left,
                                    "top": v.top,
                                    "width": m,
                                    "height": g
                                },
                                "element": {
                                    "element": u,
                                    "left": T.left,
                                    "top": T.top,
                                    "width": h,
                                    "height": d
                                },
                                "horizontal": 0 > i ? "left" : t > 0 ? "right" : "center",
                                "vertical": 0 > r ? "top" : n > 0 ? "bottom" : "middle"
                            };
                        h > m && o(t + i) < m && (l.horizontal = "center"), d > g && o(n + r) < g && (l.vertical = "middle"), l.important = a(o(t), o(i)) > a(o(n), o(r)) ? "horizontal" : "vertical", s.using.call(this, e, l)
                    }), u.offset(e.extend(T, {
                        "using": c
                    }))
                })
            }, e.ui.position = {
                "fit": {
                    "left": function (e, t) {
                        var i, n = t.within,
                            s = n.isWindow ? n.scrollLeft : n.offset.left,
                            r = n.width,
                            o = e.left - t.collisionPosition.marginLeft,
                            l = s - o,
                            c = o + t.collisionWidth - r - s;
                        t.collisionWidth > r ? l > 0 && 0 >= c ? (i = e.left + l + t.collisionWidth - r - s, e.left += l - i) : e.left = c > 0 && 0 >= l ? s : l > c ? s + r - t.collisionWidth : s : l > 0 ? e.left += l : c > 0 ? e.left -= c : e.left = a(e.left - o, e.left)
                    },
                    "top": function (e, t) {
                        var i, n = t.within,
                            s = n.isWindow ? n.scrollTop : n.offset.top,
                            r = t.within.height,
                            o = e.top - t.collisionPosition.marginTop,
                            l = s - o,
                            c = o + t.collisionHeight - r - s;
                        t.collisionHeight > r ? l > 0 && 0 >= c ? (i = e.top + l + t.collisionHeight - r - s, e.top += l - i) : e.top = c > 0 && 0 >= l ? s : l > c ? s + r - t.collisionHeight : s : l > 0 ? e.top += l : c > 0 ? e.top -= c : e.top = a(e.top - o, e.top)
                    }
                },
                "flip": {
                    "left": function (e, t) {
                        var i, n, s = t.within,
                            r = s.offset.left + s.scrollLeft,
                            a = s.width,
                            l = s.isWindow ? s.scrollLeft : s.offset.left,
                            c = e.left - t.collisionPosition.marginLeft,
                            u = c - l,
                            h = c + t.collisionWidth - a - l,
                            d = "left" === t.my[0] ? -t.elemWidth : "right" === t.my[0] ? t.elemWidth : 0,
                            p = "left" === t.at[0] ? t.targetWidth : "right" === t.at[0] ? -t.targetWidth : 0,
                            f = -2 * t.offset[0];
                        0 > u ? (i = e.left + d + p + f + t.collisionWidth - a - r, (0 > i || i < o(u)) && (e.left += d + p + f)) : h > 0 && (n = e.left - t.collisionPosition.marginLeft + d + p + f - l, (n > 0 || o(n) < h) && (e.left += d + p + f))
                    },
                    "top": function (e, t) {
                        var i, n, s = t.within,
                            r = s.offset.top + s.scrollTop,
                            a = s.height,
                            l = s.isWindow ? s.scrollTop : s.offset.top,
                            c = e.top - t.collisionPosition.marginTop,
                            u = c - l,
                            h = c + t.collisionHeight - a - l,
                            d = "top" === t.my[1],
                            p = d ? -t.elemHeight : "bottom" === t.my[1] ? t.elemHeight : 0,
                            f = "top" === t.at[1] ? t.targetHeight : "bottom" === t.at[1] ? -t.targetHeight : 0,
                            m = -2 * t.offset[1];
                        0 > u ? (n = e.top + p + f + m + t.collisionHeight - a - r, (0 > n || n < o(u)) && (e.top += p + f + m)) : h > 0 && (i = e.top - t.collisionPosition.marginTop + p + f + m - l, (i > 0 || o(i) < h) && (e.top += p + f + m))
                    }
                },
                "flipfit": {
                    "left": function () {
                        e.ui.position.flip.left.apply(this, arguments), e.ui.position.fit.left.apply(this, arguments)
                    },
                    "top": function () {
                        e.ui.position.flip.top.apply(this, arguments), e.ui.position.fit.top.apply(this, arguments)
                    }
                }
            },
            function () {
                var t, i, n, s, a, o = document.getElementsByTagName("body")[0],
                    l = document.createElement("div");
                t = document.createElement(o ? "div" : "body"), n = {
                    "visibility": "hidden",
                    "width": 0,
                    "height": 0,
                    "border": 0,
                    "margin": 0,
                    "background": "none"
                }, o && e.extend(n, {
                    "position": "absolute",
                    "left": "-1000px",
                    "top": "-1000px"
                });
                for (a in n) t.style[a] = n[a];
                t.appendChild(l), i = o || document.documentElement, i.insertBefore(t, i.firstChild), l.style.cssText = "position: absolute; left: 10.7432222px;", s = e(l).offset().left, r = s > 10 && 11 > s, t.innerHTML = "", i.removeChild(t)
            }()
    }(), e.ui.position
}),
/*!
 * jQuery UI Menu 1.11.4
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/menu/
 */
function (e) {
    "function" == typeof define && define.amd ? define(["jquery", "./core", "./widget", "./position"], e) : e(jQuery)
}(function (e) {
    return e.widget("ui.menu", {
        "version": "1.11.4",
        "defaultElement": "<ul>",
        "delay": 300,
        "options": {
            "icons": {
                "submenu": "ui-icon-carat-1-e"
            },
            "items": "> *",
            "menus": "ul",
            "position": {
                "my": "left-1 top",
                "at": "right top"
            },
            "role": "menu",
            "blur": null,
            "focus": null,
            "select": null
        },
        "_create": function () {
            this.activeMenu = this.element, this.mouseHandled = !1, this.element.uniqueId().addClass("ui-menu ui-widget ui-widget-content").toggleClass("ui-menu-icons", !!this.element.find(".ui-icon").length).attr({
                "role": this.options.role,
                "tabIndex": 0
            }), this.options.disabled && this.element.addClass("ui-state-disabled").attr("aria-disabled", "true"), this._on({
                "mousedown .ui-menu-item": function (e) {
                    e.preventDefault()
                },
                "click .ui-menu-item": function (t) {
                    var i = e(t.target);
                    !this.mouseHandled && i.not(".ui-state-disabled").length && (this.select(t), t.isPropagationStopped() || (this.mouseHandled = !0), i.has(".ui-menu").length ? this.expand(t) : !this.element.is(":focus") && e(this.document[0].activeElement).closest(".ui-menu").length && (this.element.trigger("focus", [!0]), this.active && 1 === this.active.parents(".ui-menu").length && clearTimeout(this.timer)))
                },
                "mouseenter .ui-menu-item": function (t) {
                    if (!this.previousFilter) {
                        var i = e(t.currentTarget);
                        i.siblings(".ui-state-active").removeClass("ui-state-active"), this.focus(t, i)
                    }
                },
                "mouseleave": "collapseAll",
                "mouseleave .ui-menu": "collapseAll",
                "focus": function (e, t) {
                    var i = this.active || this.element.find(this.options.items).eq(0);
                    t || this.focus(e, i)
                },
                "blur": function (t) {
                    this._delay(function () {
                        e.contains(this.element[0], this.document[0].activeElement) || this.collapseAll(t)
                    })
                },
                "keydown": "_keydown"
            }), this.refresh(), this._on(this.document, {
                "click": function (e) {
                    this._closeOnDocumentClick(e) && this.collapseAll(e), this.mouseHandled = !1
                }
            })
        },
        "_destroy": function () {
            this.element.removeAttr("aria-activedescendant").find(".ui-menu").addBack().removeClass("ui-menu ui-widget ui-widget-content ui-menu-icons ui-front").removeAttr("role").removeAttr("tabIndex").removeAttr("aria-labelledby").removeAttr("aria-expanded").removeAttr("aria-hidden").removeAttr("aria-disabled").removeUniqueId().show(), this.element.find(".ui-menu-item").removeClass("ui-menu-item").removeAttr("role").removeAttr("aria-disabled").removeUniqueId().removeClass("ui-state-hover").removeAttr("tabIndex").removeAttr("role").removeAttr("aria-haspopup").children().each(function () {
                var t = e(this);
                t.data("ui-menu-submenu-carat") && t.remove()
            }), this.element.find(".ui-menu-divider").removeClass("ui-menu-divider ui-widget-content")
        },
        "_keydown": function (t) {
            var i, n, s, r, a = !0;
            switch (t.keyCode) {
                case e.ui.keyCode.PAGE_UP:
                    this.previousPage(t);
                    break;
                case e.ui.keyCode.PAGE_DOWN:
                    this.nextPage(t);
                    break;
                case e.ui.keyCode.HOME:
                    this._move("first", "first", t);
                    break;
                case e.ui.keyCode.END:
                    this._move("last", "last", t);
                    break;
                case e.ui.keyCode.UP:
                    this.previous(t);
                    break;
                case e.ui.keyCode.DOWN:
                    this.next(t);
                    break;
                case e.ui.keyCode.LEFT:
                    this.collapse(t);
                    break;
                case e.ui.keyCode.RIGHT:
                    this.active && !this.active.is(".ui-state-disabled") && this.expand(t);
                    break;
                case e.ui.keyCode.ENTER:
                case e.ui.keyCode.SPACE:
                    this._activate(t);
                    break;
                case e.ui.keyCode.ESCAPE:
                    this.collapse(t);
                    break;
                default:
                    a = !1, n = this.previousFilter || "", s = String.fromCharCode(t.keyCode), r = !1, clearTimeout(this.filterTimer), s === n ? r = !0 : s = n + s, i = this._filterMenuItems(s), i = r && -1 !== i.index(this.active.next()) ? this.active.nextAll(".ui-menu-item") : i, i.length || (s = String.fromCharCode(t.keyCode), i = this._filterMenuItems(s)), i.length ? (this.focus(t, i), this.previousFilter = s, this.filterTimer = this._delay(function () {
                        delete this.previousFilter
                    }, 1e3)) : delete this.previousFilter
            }
            a && t.preventDefault()
        },
        "_activate": function (e) {
            this.active.is(".ui-state-disabled") || (this.active.is("[aria-haspopup='true']") ? this.expand(e) : this.select(e))
        },
        "refresh": function () {
            var t, i, n = this,
                s = this.options.icons.submenu,
                r = this.element.find(this.options.menus);
            this.element.toggleClass("ui-menu-icons", !!this.element.find(".ui-icon").length), r.filter(":not(.ui-menu)").addClass("ui-menu ui-widget ui-widget-content ui-front").hide().attr({
                "role": this.options.role,
                "aria-hidden": "true",
                "aria-expanded": "false"
            }).each(function () {
                var t = e(this),
                    i = t.parent(),
                    n = e("<span>").addClass("ui-menu-icon ui-icon " + s).data("ui-menu-submenu-carat", !0);
                i.attr("aria-haspopup", "true").prepend(n), t.attr("aria-labelledby", i.attr("id"))
            }), t = r.add(this.element), i = t.find(this.options.items), i.not(".ui-menu-item").each(function () {
                var t = e(this);
                n._isDivider(t) && t.addClass("ui-widget-content ui-menu-divider")
            }), i.not(".ui-menu-item, .ui-menu-divider").addClass("ui-menu-item").uniqueId().attr({
                "tabIndex": -1,
                "role": this._itemRole()
            }), i.filter(".ui-state-disabled").attr("aria-disabled", "true"), this.active && !e.contains(this.element[0], this.active[0]) && this.blur()
        },
        "_itemRole": function () {
            return {
                "menu": "menuitem",
                "listbox": "option"
            }[this.options.role]
        },
        "_setOption": function (e, t) {
            "icons" === e && this.element.find(".ui-menu-icon").removeClass(this.options.icons.submenu).addClass(t.submenu), "disabled" === e && this.element.toggleClass("ui-state-disabled", !!t).attr("aria-disabled", t), this._super(e, t)
        },
        "focus": function (e, t) {
            var i, n;
            this.blur(e, e && "focus" === e.type), this._scrollIntoView(t), this.active = t.first(), n = this.active.addClass("ui-state-focus").removeClass("ui-state-active"), this.options.role && this.element.attr("aria-activedescendant", n.attr("id")), this.active.parent().closest(".ui-menu-item").addClass("ui-state-active"), e && "keydown" === e.type ? this._close() : this.timer = this._delay(function () {
                this._close()
            }, this.delay), i = t.children(".ui-menu"), i.length && e && /^mouse/.test(e.type) && this._startOpening(i), this.activeMenu = t.parent(), this._trigger("focus", e, {
                "item": t
            })
        },
        "_scrollIntoView": function (t) {
            var i, n, s, r, a, o;
            this._hasScroll() && (i = parseFloat(e.css(this.activeMenu[0], "borderTopWidth")) || 0, n = parseFloat(e.css(this.activeMenu[0], "paddingTop")) || 0, s = t.offset().top - this.activeMenu.offset().top - i - n, r = this.activeMenu.scrollTop(), a = this.activeMenu.height(), o = t.outerHeight(), 0 > s ? this.activeMenu.scrollTop(r + s) : s + o > a && this.activeMenu.scrollTop(r + s - a + o))
        },
        "blur": function (e, t) {
            t || clearTimeout(this.timer), this.active && (this.active.removeClass("ui-state-focus"), this.active = null, this._trigger("blur", e, {
                "item": this.active
            }))
        },
        "_startOpening": function (e) {
            clearTimeout(this.timer), "true" === e.attr("aria-hidden") && (this.timer = this._delay(function () {
                this._close(), this._open(e)
            }, this.delay))
        },
        "_open": function (t) {
            var i = e.extend({
                "of": this.active
            }, this.options.position);
            clearTimeout(this.timer), this.element.find(".ui-menu").not(t.parents(".ui-menu")).hide().attr("aria-hidden", "true"), t.show().removeAttr("aria-hidden").attr("aria-expanded", "true").position(i)
        },
        "collapseAll": function (t, i) {
            clearTimeout(this.timer), this.timer = this._delay(function () {
                var n = i ? this.element : e(t && t.target).closest(this.element.find(".ui-menu"));
                n.length || (n = this.element), this._close(n), this.blur(t), this.activeMenu = n
            }, this.delay)
        },
        "_close": function (e) {
            e || (e = this.active ? this.active.parent() : this.element), e.find(".ui-menu").hide().attr("aria-hidden", "true").attr("aria-expanded", "false").end().find(".ui-state-active").not(".ui-state-focus").removeClass("ui-state-active")
        },
        "_closeOnDocumentClick": function (t) {
            return !e(t.target).closest(".ui-menu").length
        },
        "_isDivider": function (e) {
            return !/[^\-\u2014\u2013\s]/.test(e.text())
        },
        "collapse": function (e) {
            var t = this.active && this.active.parent().closest(".ui-menu-item", this.element);
            t && t.length && (this._close(), this.focus(e, t))
        },
        "expand": function (e) {
            var t = this.active && this.active.children(".ui-menu ").find(this.options.items).first();
            t && t.length && (this._open(t.parent()), this._delay(function () {
                this.focus(e, t)
            }))
        },
        "next": function (e) {
            this._move("next", "first", e)
        },
        "previous": function (e) {
            this._move("prev", "last", e)
        },
        "isFirstItem": function () {
            return this.active && !this.active.prevAll(".ui-menu-item").length
        },
        "isLastItem": function () {
            return this.active && !this.active.nextAll(".ui-menu-item").length
        },
        "_move": function (e, t, i) {
            var n;
            this.active && (n = "first" === e || "last" === e ? this.active["first" === e ? "prevAll" : "nextAll"](".ui-menu-item").eq(-1) : this.active[e + "All"](".ui-menu-item").eq(0)), n && n.length && this.active || (n = this.activeMenu.find(this.options.items)[t]()), this.focus(i, n)
        },
        "nextPage": function (t) {
            var i, n, s;
            return this.active ? void(this.isLastItem() || (this._hasScroll() ? (n = this.active.offset().top, s = this.element.height(), this.active.nextAll(".ui-menu-item").each(function () {
                return i = e(this), i.offset().top - n - s < 0
            }), this.focus(t, i)) : this.focus(t, this.activeMenu.find(this.options.items)[this.active ? "last" : "first"]()))) : void this.next(t)
        },
        "previousPage": function (t) {
            var i, n, s;
            return this.active ? void(this.isFirstItem() || (this._hasScroll() ? (n = this.active.offset().top, s = this.element.height(), this.active.prevAll(".ui-menu-item").each(function () {
                return i = e(this), i.offset().top - n + s > 0
            }), this.focus(t, i)) : this.focus(t, this.activeMenu.find(this.options.items).first()))) : void this.next(t)
        },
        "_hasScroll": function () {
            return this.element.outerHeight() < this.element.prop("scrollHeight")
        },
        "select": function (t) {
            this.active = this.active || e(t.target).closest(".ui-menu-item");
            var i = {
                "item": this.active
            };
            this.active.has(".ui-menu").length || this.collapseAll(t, !0), this._trigger("select", t, i)
        },
        "_filterMenuItems": function (t) {
            var i = t.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&"),
                n = new RegExp("^" + i, "i");
            return this.activeMenu.find(this.options.items).filter(".ui-menu-item").filter(function () {
                return n.test(e.trim(e(this).text()))
            })
        }
    })
}),
/*!
 * jQuery UI Autocomplete 1.11.4
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/autocomplete/
 */
function (e) {
    "function" == typeof define && define.amd ? define(["jquery", "./core", "./widget", "./position", "./menu"], e) : e(jQuery)
}(function (e) {
    return e.widget("ui.autocomplete", {
        "version": "1.11.4",
        "defaultElement": "<input>",
        "options": {
            "appendTo": null,
            "autoFocus": !1,
            "delay": 300,
            "minLength": 1,
            "position": {
                "my": "left top",
                "at": "left bottom",
                "collision": "none"
            },
            "source": null,
            "change": null,
            "close": null,
            "focus": null,
            "open": null,
            "response": null,
            "search": null,
            "select": null
        },
        "requestIndex": 0,
        "pending": 0,
        "_create": function () {
            var t, i, n, s = this.element[0].nodeName.toLowerCase(),
                r = "textarea" === s,
                a = "input" === s;
            this.isMultiLine = r ? !0 : a ? !1 : this.element.prop("isContentEditable"), this.valueMethod = this.element[r || a ? "val" : "text"], this.isNewMenu = !0, this.element.addClass("ui-autocomplete-input").attr("autocomplete", "off"), this._on(this.element, {
                "keydown": function (s) {
                    if (this.element.prop("readOnly")) return t = !0, n = !0, void(i = !0);
                    t = !1, n = !1, i = !1;
                    var r = e.ui.keyCode;
                    switch (s.keyCode) {
                        case r.PAGE_UP:
                            t = !0, this._move("previousPage", s);
                            break;
                        case r.PAGE_DOWN:
                            t = !0, this._move("nextPage", s);
                            break;
                        case r.UP:
                            t = !0, this._keyEvent("previous", s);
                            break;
                        case r.DOWN:
                            t = !0, this._keyEvent("next", s);
                            break;
                        case r.ENTER:
                            this.menu.active && (t = !0, s.preventDefault(), this.menu.select(s));
                            break;
                        case r.TAB:
                            this.menu.active && this.menu.select(s);
                            break;
                        case r.ESCAPE:
                            this.menu.element.is(":visible") && (this.isMultiLine || this._value(this.term), this.close(s), s.preventDefault());
                            break;
                        default:
                            i = !0, this._searchTimeout(s)
                    }
                },
                "keypress": function (n) {
                    if (t) return t = !1, void((!this.isMultiLine || this.menu.element.is(":visible")) && n.preventDefault());
                    if (!i) {
                        var s = e.ui.keyCode;
                        switch (n.keyCode) {
                            case s.PAGE_UP:
                                this._move("previousPage", n);
                                break;
                            case s.PAGE_DOWN:
                                this._move("nextPage", n);
                                break;
                            case s.UP:
                                this._keyEvent("previous", n);
                                break;
                            case s.DOWN:
                                this._keyEvent("next", n)
                        }
                    }
                },
                "input": function (e) {
                    return n ? (n = !1, void e.preventDefault()) : void this._searchTimeout(e)
                },
                "focus": function () {
                    this.selectedItem = null, this.previous = this._value()
                },
                "blur": function (e) {
                    return this.cancelBlur ? void delete this.cancelBlur : (clearTimeout(this.searching), this.close(e), void this._change(e))
                }
            }), this._initSource(), this.menu = e("<ul>").addClass("ui-autocomplete ui-front").appendTo(this._appendTo()).menu({
                "role": null
            }).hide().menu("instance"), this._on(this.menu.element, {
                "mousedown": function (t) {
                    t.preventDefault(), this.cancelBlur = !0, this._delay(function () {
                        delete this.cancelBlur
                    });
                    var i = this.menu.element[0];
                    e(t.target).closest(".ui-menu-item").length || this._delay(function () {
                        var t = this;
                        this.document.one("mousedown", function (n) {
                            n.target === t.element[0] || n.target === i || e.contains(i, n.target) || t.close()
                        })
                    })
                },
                "menufocus": function (t, i) {
                    var n, s;
                    return this.isNewMenu && (this.isNewMenu = !1, t.originalEvent && /^mouse/.test(t.originalEvent.type)) ? (this.menu.blur(), void this.document.one("mousemove", function () {
                        e(t.target).trigger(t.originalEvent)
                    })) : (s = i.item.data("ui-autocomplete-item"), !1 !== this._trigger("focus", t, {
                        "item": s
                    }) && t.originalEvent && /^key/.test(t.originalEvent.type) && this._value(s.value), n = i.item.attr("aria-label") || s.value, void(n && e.trim(n).length && (this.liveRegion.children().hide(), e("<div>").text(n).appendTo(this.liveRegion))))
                },
                "menuselect": function (e, t) {
                    var i = t.item.data("ui-autocomplete-item"),
                        n = this.previous;
                    this.element[0] !== this.document[0].activeElement && (this.element.focus(), this.previous = n, this._delay(function () {
                        this.previous = n, this.selectedItem = i
                    })), !1 !== this._trigger("select", e, {
                        "item": i
                    }) && this._value(i.value), this.term = this._value(), this.close(e), this.selectedItem = i
                }
            }), this.liveRegion = e("<span>", {
                "role": "status",
                "aria-live": "assertive",
                "aria-relevant": "additions"
            }).addClass("ui-helper-hidden-accessible").appendTo(this.document[0].body), this._on(this.window, {
                "beforeunload": function () {
                    this.element.removeAttr("autocomplete")
                }
            })
        },
        "_destroy": function () {
            clearTimeout(this.searching), this.element.removeClass("ui-autocomplete-input").removeAttr("autocomplete"), this.menu.element.remove(), this.liveRegion.remove()
        },
        "_setOption": function (e, t) {
            this._super(e, t), "source" === e && this._initSource(), "appendTo" === e && this.menu.element.appendTo(this._appendTo()), "disabled" === e && t && this.xhr && this.xhr.abort()
        },
        "_appendTo": function () {
            var t = this.options.appendTo;
            return t && (t = t.jquery || t.nodeType ? e(t) : this.document.find(t).eq(0)), t && t[0] || (t = this.element.closest(".ui-front")), t.length || (t = this.document[0].body), t
        },
        "_initSource": function () {
            var t, i, n = this;
            e.isArray(this.options.source) ? (t = this.options.source, this.source = function (i, n) {
                n(e.ui.autocomplete.filter(t, i.term))
            }) : "string" == typeof this.options.source ? (i = this.options.source, this.source = function (t, s) {
                n.xhr && n.xhr.abort(), n.xhr = e.ajax({
                    "url": i,
                    "data": t,
                    "dataType": "json",
                    "success": function (e) {
                        s(e)
                    },
                    "error": function () {
                        s([])
                    }
                })
            }) : this.source = this.options.source
        },
        "_searchTimeout": function (e) {
            clearTimeout(this.searching), this.searching = this._delay(function () {
                var t = this.term === this._value(),
                    i = this.menu.element.is(":visible"),
                    n = e.altKey || e.ctrlKey || e.metaKey || e.shiftKey;
                (!t || t && !i && !n) && (this.selectedItem = null, this.search(null, e))
            }, this.options.delay)
        },
        "search": function (e, t) {
            return e = null != e ? e : this._value(), this.term = this._value(), e.length < this.options.minLength ? this.close(t) : this._trigger("search", t) !== !1 ? this._search(e) : void 0
        },
        "_search": function (e) {
            this.pending++, this.element.addClass("ui-autocomplete-loading"), this.cancelSearch = !1, this.source({
                "term": e
            }, this._response())
        },
        "_response": function () {
            var t = ++this.requestIndex;
            return e.proxy(function (e) {
                t === this.requestIndex && this.__response(e), this.pending--, this.pending || this.element.removeClass("ui-autocomplete-loading")
            }, this)
        },
        "__response": function (e) {
            e && (e = this._normalize(e)), this._trigger("response", null, {
                "content": e
            }), !this.options.disabled && e && e.length && !this.cancelSearch ? (this._suggest(e), this._trigger("open")) : this._close()
        },
        "close": function (e) {
            this.cancelSearch = !0, this._close(e)
        },
        "_close": function (e) {
            this.menu.element.is(":visible") && (this.menu.element.hide(), this.menu.blur(), this.isNewMenu = !0, this._trigger("close", e))
        },
        "_change": function (e) {
            this.previous !== this._value() && this._trigger("change", e, {
                "item": this.selectedItem
            })
        },
        "_normalize": function (t) {
            return t.length && t[0].label && t[0].value ? t : e.map(t, function (t) {
                return "string" == typeof t ? {
                    "label": t,
                    "value": t
                } : e.extend({}, t, {
                    "label": t.label || t.value,
                    "value": t.value || t.label
                })
            })
        },
        "_suggest": function (t) {
            var i = this.menu.element.empty();
            this._renderMenu(i, t), this.isNewMenu = !0, this.menu.refresh(), i.show(), this._resizeMenu(), i.position(e.extend({
                "of": this.element
            }, this.options.position)), this.options.autoFocus && this.menu.next()
        },
        "_resizeMenu": function () {
            var e = this.menu.element;
            e.outerWidth(Math.max(e.width("").outerWidth() + 1, this.element.outerWidth()))
        },
        "_renderMenu": function (t, i) {
            var n = this;
            e.each(i, function (e, i) {
                n._renderItemData(t, i)
            })
        },
        "_renderItemData": function (e, t) {
            return this._renderItem(e, t).data("ui-autocomplete-item", t)
        },
        "_renderItem": function (t, i) {
            return e("<li>").text(i.label).appendTo(t)
        },
        "_move": function (e, t) {
            return this.menu.element.is(":visible") ? this.menu.isFirstItem() && /^previous/.test(e) || this.menu.isLastItem() && /^next/.test(e) ? (this.isMultiLine || this._value(this.term), void this.menu.blur()) : void this.menu[e](t) : void this.search(null, t)
        },
        "widget": function () {
            return this.menu.element
        },
        "_value": function () {
            return this.valueMethod.apply(this.element, arguments)
        },
        "_keyEvent": function (e, t) {
            (!this.isMultiLine || this.menu.element.is(":visible")) && (this._move(e, t), t.preventDefault())
        }
    }), e.extend(e.ui.autocomplete, {
        "escapeRegex": function (e) {
            return e.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&")
        },
        "filter": function (t, i) {
            var n = new RegExp(e.ui.autocomplete.escapeRegex(i), "i");
            return e.grep(t, function (e) {
                return n.test(e.label || e.value || e)
            })
        }
    }), e.widget("ui.autocomplete", e.ui.autocomplete, {
        "options": {
            "messages": {
                "noResults": "No search results.",
                "results": function (e) {
                    return e + (e > 1 ? " results are" : " result is") + " available, use up and down arrow keys to navigate."
                }
            }
        },
        "__response": function (t) {
            var i;
            this._superApply(arguments), this.options.disabled || this.cancelSearch || (i = t && t.length ? this.options.messages.results(t.length) : this.options.messages.noResults, this.liveRegion.children().hide(), e("<div>").text(i).appendTo(this.liveRegion))
        }
    }), e.ui.autocomplete
}),
/*!
 * jQuery UI Datepicker 1.11.4
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/datepicker/
 */
function (e) {
    "function" == typeof define && define.amd ? define(["jquery", "./core"], e) : e(jQuery)
}(function (e) {
    function t(e) {
        for (var t, i; e.length && e[0] !== document;) {
            if (t = e.css("position"), ("absolute" === t || "relative" === t || "fixed" === t) && (i = parseInt(e.css("zIndex"), 10), !isNaN(i) && 0 !== i)) return i;
            e = e.parent()
        }
        return 0
    }

    function i() {
        this._curInst = null, this._keyEvent = !1, this._disabledInputs = [], this._datepickerShowing = !1, this._inDialog = !1, this._mainDivId = "ui-datepicker-div", this._inlineClass = "ui-datepicker-inline", this._appendClass = "ui-datepicker-append", this._triggerClass = "ui-datepicker-trigger", this._dialogClass = "ui-datepicker-dialog", this._disableClass = "ui-datepicker-disabled", this._unselectableClass = "ui-datepicker-unselectable", this._currentClass = "ui-datepicker-current-day", this._dayOverClass = "ui-datepicker-days-cell-over", this.regional = [], this.regional[""] = {
            "closeText": "Done",
            "prevText": "Prev",
            "nextText": "Next",
            "currentText": "Today",
            "monthNames": ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            "monthNamesShort": ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            "dayNames": ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
            "dayNamesShort": ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            "dayNamesMin": ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
            "weekHeader": "Wk",
            "dateFormat": "mm/dd/yy",
            "firstDay": 0,
            "isRTL": !1,
            "showMonthAfterYear": !1,
            "yearSuffix": ""
        }, this._defaults = {
            "showOn": "focus",
            "showAnim": "fadeIn",
            "showOptions": {},
            "defaultDate": null,
            "appendText": "",
            "buttonText": "...",
            "buttonImage": "",
            "buttonImageOnly": !1,
            "hideIfNoPrevNext": !1,
            "navigationAsDateFormat": !1,
            "gotoCurrent": !1,
            "changeMonth": !1,
            "changeYear": !1,
            "yearRange": "c-10:c+10",
            "showOtherMonths": !1,
            "selectOtherMonths": !1,
            "showWeek": !1,
            "calculateWeek": this.iso8601Week,
            "shortYearCutoff": "+10",
            "minDate": null,
            "maxDate": null,
            "duration": "fast",
            "beforeShowDay": null,
            "beforeShow": null,
            "onSelect": null,
            "onChangeMonthYear": null,
            "onClose": null,
            "numberOfMonths": 1,
            "showCurrentAtPos": 0,
            "stepMonths": 1,
            "stepBigMonths": 12,
            "altField": "",
            "altFormat": "",
            "constrainInput": !0,
            "showButtonPanel": !1,
            "autoSize": !1,
            "disabled": !1
        }, e.extend(this._defaults, this.regional[""]), this.regional.en = e.extend(!0, {}, this.regional[""]), this.regional["en-US"] = e.extend(!0, {}, this.regional.en), this.dpDiv = n(e("<div id='" + this._mainDivId + "' class='ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>"))
    }

    function n(t) {
        var i = "button, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-calendar td a";
        return t.delegate(i, "mouseout", function () {
            e(this).removeClass("ui-state-hover"), -1 !== this.className.indexOf("ui-datepicker-prev") && e(this).removeClass("ui-datepicker-prev-hover"), -1 !== this.className.indexOf("ui-datepicker-next") && e(this).removeClass("ui-datepicker-next-hover")
        }).delegate(i, "mouseover", s)
    }

    function s() {
        e.datepicker._isDisabledDatepicker(a.inline ? a.dpDiv.parent()[0] : a.input[0]) || (e(this).parents(".ui-datepicker-calendar").find("a").removeClass("ui-state-hover"), e(this).addClass("ui-state-hover"), -1 !== this.className.indexOf("ui-datepicker-prev") && e(this).addClass("ui-datepicker-prev-hover"), -1 !== this.className.indexOf("ui-datepicker-next") && e(this).addClass("ui-datepicker-next-hover"))
    }

    function r(t, i) {
        e.extend(t, i);
        for (var n in i) null == i[n] && (t[n] = i[n]);
        return t
    }
    e.extend(e.ui, {
        "datepicker": {
            "version": "1.11.4"
        }
    });
    var a;
    return e.extend(i.prototype, {
        "markerClassName": "hasDatepicker",
        "maxRows": 4,
        "_widgetDatepicker": function () {
            return this.dpDiv
        },
        "setDefaults": function (e) {
            return r(this._defaults, e || {}), this
        },
        "_attachDatepicker": function (t, i) {
            var n, s, r;
            n = t.nodeName.toLowerCase(), s = "div" === n || "span" === n, t.id || (this.uuid += 1, t.id = "dp" + this.uuid), r = this._newInst(e(t), s), r.settings = e.extend({}, i || {}), "input" === n ? this._connectDatepicker(t, r) : s && this._inlineDatepicker(t, r)
        },
        "_newInst": function (t, i) {
            var s = t[0].id.replace(/([^A-Za-z0-9_\-])/g, "\\\\$1");
            return {
                "id": s,
                "input": t,
                "selectedDay": 0,
                "selectedMonth": 0,
                "selectedYear": 0,
                "drawMonth": 0,
                "drawYear": 0,
                "inline": i,
                "dpDiv": i ? n(e("<div class='" + this._inlineClass + " ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>")) : this.dpDiv
            }
        },
        "_connectDatepicker": function (t, i) {
            var n = e(t);
            i.append = e([]), i.trigger = e([]), n.hasClass(this.markerClassName) || (this._attachments(n, i), n.addClass(this.markerClassName).keydown(this._doKeyDown).keypress(this._doKeyPress).keyup(this._doKeyUp), this._autoSize(i), e.data(t, "datepicker", i), i.settings.disabled && this._disableDatepicker(t))
        },
        "_attachments": function (t, i) {
            var n, s, r, a = this._get(i, "appendText"),
                o = this._get(i, "isRTL");
            i.append && i.append.remove(), a && (i.append = e("<span class='" + this._appendClass + "'>" + a + "</span>"), t[o ? "before" : "after"](i.append)), t.unbind("focus", this._showDatepicker), i.trigger && i.trigger.remove(), n = this._get(i, "showOn"), ("focus" === n || "both" === n) && t.focus(this._showDatepicker), ("button" === n || "both" === n) && (s = this._get(i, "buttonText"), r = this._get(i, "buttonImage"), i.trigger = e(this._get(i, "buttonImageOnly") ? e("<img/>").addClass(this._triggerClass).attr({
                "src": r,
                "alt": s,
                "title": s
            }) : e("<button type='button'></button>").addClass(this._triggerClass).html(r ? e("<img/>").attr({
                "src": r,
                "alt": s,
                "title": s
            }) : s)), t[o ? "before" : "after"](i.trigger), i.trigger.click(function () {
                return e.datepicker._datepickerShowing && e.datepicker._lastInput === t[0] ? e.datepicker._hideDatepicker() : e.datepicker._datepickerShowing && e.datepicker._lastInput !== t[0] ? (e.datepicker._hideDatepicker(), e.datepicker._showDatepicker(t[0])) : e.datepicker._showDatepicker(t[0]), !1
            }))
        },
        "_autoSize": function (e) {
            if (this._get(e, "autoSize") && !e.inline) {
                var t, i, n, s, r = new Date(2009, 11, 20),
                    a = this._get(e, "dateFormat");
                a.match(/[DM]/) && (t = function (e) {
                    for (i = 0, n = 0, s = 0; s < e.length; s++) e[s].length > i && (i = e[s].length, n = s);
                    return n
                }, r.setMonth(t(this._get(e, a.match(/MM/) ? "monthNames" : "monthNamesShort"))), r.setDate(t(this._get(e, a.match(/DD/) ? "dayNames" : "dayNamesShort")) + 20 - r.getDay())), e.input.attr("size", this._formatDate(e, r).length)
            }
        },
        "_inlineDatepicker": function (t, i) {
            var n = e(t);
            n.hasClass(this.markerClassName) || (n.addClass(this.markerClassName).append(i.dpDiv), e.data(t, "datepicker", i), this._setDate(i, this._getDefaultDate(i), !0), this._updateDatepicker(i), this._updateAlternate(i), i.settings.disabled && this._disableDatepicker(t), i.dpDiv.css("display", "block"))
        },
        "_dialogDatepicker": function (t, i, n, s, a) {
            var o, l, c, u, h, d = this._dialogInst;
            return d || (this.uuid += 1, o = "dp" + this.uuid, this._dialogInput = e("<input type='text' id='" + o + "' style='position: absolute; top: -100px; width: 0px;'/>"), this._dialogInput.keydown(this._doKeyDown), e("body").append(this._dialogInput), d = this._dialogInst = this._newInst(this._dialogInput, !1), d.settings = {}, e.data(this._dialogInput[0], "datepicker", d)), r(d.settings, s || {}), i = i && i.constructor === Date ? this._formatDate(d, i) : i, this._dialogInput.val(i), this._pos = a ? a.length ? a : [a.pageX, a.pageY] : null, this._pos || (l = document.documentElement.clientWidth, c = document.documentElement.clientHeight, u = document.documentElement.scrollLeft || document.body.scrollLeft, h = document.documentElement.scrollTop || document.body.scrollTop, this._pos = [l / 2 - 100 + u, c / 2 - 150 + h]), this._dialogInput.css("left", this._pos[0] + 20 + "px").css("top", this._pos[1] + "px"), d.settings.onSelect = n, this._inDialog = !0, this.dpDiv.addClass(this._dialogClass), this._showDatepicker(this._dialogInput[0]), e.blockUI && e.blockUI(this.dpDiv), e.data(this._dialogInput[0], "datepicker", d), this
        },
        "_destroyDatepicker": function (t) {
            var i, n = e(t),
                s = e.data(t, "datepicker");
            n.hasClass(this.markerClassName) && (i = t.nodeName.toLowerCase(), e.removeData(t, "datepicker"), "input" === i ? (s.append.remove(), s.trigger.remove(), n.removeClass(this.markerClassName).unbind("focus", this._showDatepicker).unbind("keydown", this._doKeyDown).unbind("keypress", this._doKeyPress).unbind("keyup", this._doKeyUp)) : ("div" === i || "span" === i) && n.removeClass(this.markerClassName).empty(), a === s && (a = null))
        },
        "_enableDatepicker": function (t) {
            var i, n, s = e(t),
                r = e.data(t, "datepicker");
            s.hasClass(this.markerClassName) && (i = t.nodeName.toLowerCase(), "input" === i ? (t.disabled = !1, r.trigger.filter("button").each(function () {
                this.disabled = !1
            }).end().filter("img").css({
                "opacity": "1.0",
                "cursor": ""
            })) : ("div" === i || "span" === i) && (n = s.children("." + this._inlineClass), n.children().removeClass("ui-state-disabled"), n.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled", !1)), this._disabledInputs = e.map(this._disabledInputs, function (e) {
                return e === t ? null : e
            }))
        },
        "_disableDatepicker": function (t) {
            var i, n, s = e(t),
                r = e.data(t, "datepicker");
            s.hasClass(this.markerClassName) && (i = t.nodeName.toLowerCase(), "input" === i ? (t.disabled = !0, r.trigger.filter("button").each(function () {
                this.disabled = !0
            }).end().filter("img").css({
                "opacity": "0.5",
                "cursor": "default"
            })) : ("div" === i || "span" === i) && (n = s.children("." + this._inlineClass), n.children().addClass("ui-state-disabled"), n.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled", !0)), this._disabledInputs = e.map(this._disabledInputs, function (e) {
                return e === t ? null : e
            }), this._disabledInputs[this._disabledInputs.length] = t)
        },
        "_isDisabledDatepicker": function (e) {
            if (!e) return !1;
            for (var t = 0; t < this._disabledInputs.length; t++)
                if (this._disabledInputs[t] === e) return !0;
            return !1
        },
        "_getInst": function (t) {
            try {
                return e.data(t, "datepicker")
            } catch (i) {
                throw "Missing instance data for this datepicker"
            }
        },
        "_optionDatepicker": function (t, i, n) {
            var s, a, o, l, c = this._getInst(t);
            return 2 === arguments.length && "string" == typeof i ? "defaults" === i ? e.extend({}, e.datepicker._defaults) : c ? "all" === i ? e.extend({}, c.settings) : this._get(c, i) : null : (s = i || {}, "string" == typeof i && (s = {}, s[i] = n), void(c && (this._curInst === c && this._hideDatepicker(), a = this._getDateDatepicker(t, !0), o = this._getMinMaxDate(c, "min"), l = this._getMinMaxDate(c, "max"), r(c.settings, s), null !== o && void 0 !== s.dateFormat && void 0 === s.minDate && (c.settings.minDate = this._formatDate(c, o)), null !== l && void 0 !== s.dateFormat && void 0 === s.maxDate && (c.settings.maxDate = this._formatDate(c, l)), "disabled" in s && (s.disabled ? this._disableDatepicker(t) : this._enableDatepicker(t)), this._attachments(e(t), c), this._autoSize(c), this._setDate(c, a), this._updateAlternate(c), this._updateDatepicker(c))))
        },
        "_changeDatepicker": function (e, t, i) {
            this._optionDatepicker(e, t, i)
        },
        "_refreshDatepicker": function (e) {
            var t = this._getInst(e);
            t && this._updateDatepicker(t)
        },
        "_setDateDatepicker": function (e, t) {
            var i = this._getInst(e);
            i && (this._setDate(i, t), this._updateDatepicker(i), this._updateAlternate(i))
        },
        "_getDateDatepicker": function (e, t) {
            var i = this._getInst(e);
            return i && !i.inline && this._setDateFromField(i, t), i ? this._getDate(i) : null
        },
        "_doKeyDown": function (t) {
            var i, n, s, r = e.datepicker._getInst(t.target),
                a = !0,
                o = r.dpDiv.is(".ui-datepicker-rtl");
            if (r._keyEvent = !0, e.datepicker._datepickerShowing) switch (t.keyCode) {
                case 9:
                    e.datepicker._hideDatepicker(), a = !1;
                    break;
                case 13:
                    return s = e("td." + e.datepicker._dayOverClass + ":not(." + e.datepicker._currentClass + ")", r.dpDiv), s[0] && e.datepicker._selectDay(t.target, r.selectedMonth, r.selectedYear, s[0]), i = e.datepicker._get(r, "onSelect"), i ? (n = e.datepicker._formatDate(r), i.apply(r.input ? r.input[0] : null, [n, r])) : e.datepicker._hideDatepicker(), !1;
                case 27:
                    e.datepicker._hideDatepicker();
                    break;
                case 33:
                    e.datepicker._adjustDate(t.target, t.ctrlKey ? -e.datepicker._get(r, "stepBigMonths") : -e.datepicker._get(r, "stepMonths"), "M");
                    break;
                case 34:
                    e.datepicker._adjustDate(t.target, t.ctrlKey ? +e.datepicker._get(r, "stepBigMonths") : +e.datepicker._get(r, "stepMonths"), "M");
                    break;
                case 35:
                    (t.ctrlKey || t.metaKey) && e.datepicker._clearDate(t.target), a = t.ctrlKey || t.metaKey;
                    break;
                case 36:
                    (t.ctrlKey || t.metaKey) && e.datepicker._gotoToday(t.target), a = t.ctrlKey || t.metaKey;
                    break;
                case 37:
                    (t.ctrlKey || t.metaKey) && e.datepicker._adjustDate(t.target, o ? 1 : -1, "D"), a = t.ctrlKey || t.metaKey, t.originalEvent.altKey && e.datepicker._adjustDate(t.target, t.ctrlKey ? -e.datepicker._get(r, "stepBigMonths") : -e.datepicker._get(r, "stepMonths"), "M");
                    break;
                case 38:
                    (t.ctrlKey || t.metaKey) && e.datepicker._adjustDate(t.target, -7, "D"), a = t.ctrlKey || t.metaKey;
                    break;
                case 39:
                    (t.ctrlKey || t.metaKey) && e.datepicker._adjustDate(t.target, o ? -1 : 1, "D"), a = t.ctrlKey || t.metaKey, t.originalEvent.altKey && e.datepicker._adjustDate(t.target, t.ctrlKey ? +e.datepicker._get(r, "stepBigMonths") : +e.datepicker._get(r, "stepMonths"), "M");
                    break;
                case 40:
                    (t.ctrlKey || t.metaKey) && e.datepicker._adjustDate(t.target, 7, "D"), a = t.ctrlKey || t.metaKey;
                    break;
                default:
                    a = !1
            } else 36 === t.keyCode && t.ctrlKey ? e.datepicker._showDatepicker(this) : a = !1;
            a && (t.preventDefault(), t.stopPropagation())
        },
        "_doKeyPress": function (t) {
            var i, n, s = e.datepicker._getInst(t.target);
            return e.datepicker._get(s, "constrainInput") ? (i = e.datepicker._possibleChars(e.datepicker._get(s, "dateFormat")), n = String.fromCharCode(null == t.charCode ? t.keyCode : t.charCode), t.ctrlKey || t.metaKey || " " > n || !i || i.indexOf(n) > -1) : void 0
        },
        "_doKeyUp": function (t) {
            var i, n = e.datepicker._getInst(t.target);
            if (n.input.val() !== n.lastVal) try {
                i = e.datepicker.parseDate(e.datepicker._get(n, "dateFormat"), n.input ? n.input.val() : null, e.datepicker._getFormatConfig(n)), i && (e.datepicker._setDateFromField(n), e.datepicker._updateAlternate(n), e.datepicker._updateDatepicker(n))
            } catch (s) {}
            return !0
        },
        "_showDatepicker": function (i) {
            if (i = i.target || i, "input" !== i.nodeName.toLowerCase() && (i = e("input", i.parentNode)[0]), !e.datepicker._isDisabledDatepicker(i) && e.datepicker._lastInput !== i) {
                var n, s, a, o, l, c, u;
                n = e.datepicker._getInst(i), e.datepicker._curInst && e.datepicker._curInst !== n && (e.datepicker._curInst.dpDiv.stop(!0, !0), n && e.datepicker._datepickerShowing && e.datepicker._hideDatepicker(e.datepicker._curInst.input[0])), s = e.datepicker._get(n, "beforeShow"), a = s ? s.apply(i, [i, n]) : {}, a !== !1 && (r(n.settings, a), n.lastVal = null, e.datepicker._lastInput = i, e.datepicker._setDateFromField(n), e.datepicker._inDialog && (i.value = ""), e.datepicker._pos || (e.datepicker._pos = e.datepicker._findPos(i), e.datepicker._pos[1] += i.offsetHeight), o = !1, e(i).parents().each(function () {
                    return o |= "fixed" === e(this).css("position"), !o
                }), l = {
                    "left": e.datepicker._pos[0],
                    "top": e.datepicker._pos[1]
                }, e.datepicker._pos = null, n.dpDiv.empty(), n.dpDiv.css({
                    "position": "absolute",
                    "display": "block",
                    "top": "-1000px"
                }), e.datepicker._updateDatepicker(n), l = e.datepicker._checkOffset(n, l, o), n.dpDiv.css({
                    "position": e.datepicker._inDialog && e.blockUI ? "static" : o ? "fixed" : "absolute",
                    "display": "none",
                    "left": l.left + "px",
                    "top": l.top + "px"
                }), n.inline || (c = e.datepicker._get(n, "showAnim"), u = e.datepicker._get(n, "duration"), n.dpDiv.css("z-index", t(e(i)) + 1), e.datepicker._datepickerShowing = !0, e.effects && e.effects.effect[c] ? n.dpDiv.show(c, e.datepicker._get(n, "showOptions"), u) : n.dpDiv[c || "show"](c ? u : null), e.datepicker._shouldFocusInput(n) && n.input.focus(), e.datepicker._curInst = n))
            }
        },
        "_updateDatepicker": function (t) {
            this.maxRows = 4, a = t, t.dpDiv.empty().append(this._generateHTML(t)), this._attachHandlers(t);
            var i, n = this._getNumberOfMonths(t),
                r = n[1],
                o = 17,
                l = t.dpDiv.find("." + this._dayOverClass + " a");
            l.length > 0 && s.apply(l.get(0)), t.dpDiv.removeClass("ui-datepicker-multi-2 ui-datepicker-multi-3 ui-datepicker-multi-4").width(""), r > 1 && t.dpDiv.addClass("ui-datepicker-multi-" + r).css("width", o * r + "em"), t.dpDiv[(1 !== n[0] || 1 !== n[1] ? "add" : "remove") + "Class"]("ui-datepicker-multi"), t.dpDiv[(this._get(t, "isRTL") ? "add" : "remove") + "Class"]("ui-datepicker-rtl"), t === e.datepicker._curInst && e.datepicker._datepickerShowing && e.datepicker._shouldFocusInput(t) && t.input.focus(), t.yearshtml && (i = t.yearshtml, setTimeout(function () {
                i === t.yearshtml && t.yearshtml && t.dpDiv.find("select.ui-datepicker-year:first").replaceWith(t.yearshtml), i = t.yearshtml = null
            }, 0))
        },
        "_shouldFocusInput": function (e) {
            return e.input && e.input.is(":visible") && !e.input.is(":disabled") && !e.input.is(":focus")
        },
        "_checkOffset": function (t, i, n) {
            var s = t.dpDiv.outerWidth(),
                r = t.dpDiv.outerHeight(),
                a = t.input ? t.input.outerWidth() : 0,
                o = t.input ? t.input.outerHeight() : 0,
                l = document.documentElement.clientWidth + (n ? 0 : e(document).scrollLeft()),
                c = document.documentElement.clientHeight + (n ? 0 : e(document).scrollTop());
            return i.left -= this._get(t, "isRTL") ? s - a : 0, i.left -= n && i.left === t.input.offset().left ? e(document).scrollLeft() : 0, i.top -= n && i.top === t.input.offset().top + o ? e(document).scrollTop() : 0, i.left -= Math.min(i.left, i.left + s > l && l > s ? Math.abs(i.left + s - l) : 0), i.top -= Math.min(i.top, i.top + r > c && c > r ? Math.abs(r + o) : 0), i
        },
        "_findPos": function (t) {
            for (var i, n = this._getInst(t), s = this._get(n, "isRTL"); t && ("hidden" === t.type || 1 !== t.nodeType || e.expr.filters.hidden(t));) t = t[s ? "previousSibling" : "nextSibling"];
            return i = e(t).offset(), [i.left, i.top]
        },
        "_hideDatepicker": function (t) {
            var i, n, s, r, a = this._curInst;
            !a || t && a !== e.data(t, "datepicker") || this._datepickerShowing && (i = this._get(a, "showAnim"), n = this._get(a, "duration"), s = function () {
                e.datepicker._tidyDialog(a)
            }, e.effects && (e.effects.effect[i] || e.effects[i]) ? a.dpDiv.hide(i, e.datepicker._get(a, "showOptions"), n, s) : a.dpDiv["slideDown" === i ? "slideUp" : "fadeIn" === i ? "fadeOut" : "hide"](i ? n : null, s), i || s(), this._datepickerShowing = !1, r = this._get(a, "onClose"), r && r.apply(a.input ? a.input[0] : null, [a.input ? a.input.val() : "", a]), this._lastInput = null, this._inDialog && (this._dialogInput.css({
                "position": "absolute",
                "left": "0",
                "top": "-100px"
            }), e.blockUI && (e.unblockUI(), e("body").append(this.dpDiv))), this._inDialog = !1)
        },
        "_tidyDialog": function (e) {
            e.dpDiv.removeClass(this._dialogClass).unbind(".ui-datepicker-calendar")
        },
        "_checkExternalClick": function (t) {
            if (e.datepicker._curInst) {
                var i = e(t.target),
                    n = e.datepicker._getInst(i[0]);
                (i[0].id !== e.datepicker._mainDivId && 0 === i.parents("#" + e.datepicker._mainDivId).length && !i.hasClass(e.datepicker.markerClassName) && !i.closest("." + e.datepicker._triggerClass).length && e.datepicker._datepickerShowing && (!e.datepicker._inDialog || !e.blockUI) || i.hasClass(e.datepicker.markerClassName) && e.datepicker._curInst !== n) && e.datepicker._hideDatepicker()
            }
        },
        "_adjustDate": function (t, i, n) {
            var s = e(t),
                r = this._getInst(s[0]);
            this._isDisabledDatepicker(s[0]) || (this._adjustInstDate(r, i + ("M" === n ? this._get(r, "showCurrentAtPos") : 0), n), this._updateDatepicker(r))
        },
        "_gotoToday": function (t) {
            var i, n = e(t),
                s = this._getInst(n[0]);
            this._get(s, "gotoCurrent") && s.currentDay ? (s.selectedDay = s.currentDay, s.drawMonth = s.selectedMonth = s.currentMonth, s.drawYear = s.selectedYear = s.currentYear) : (i = new Date, s.selectedDay = i.getDate(), s.drawMonth = s.selectedMonth = i.getMonth(), s.drawYear = s.selectedYear = i.getFullYear()), this._notifyChange(s), this._adjustDate(n)
        },
        "_selectMonthYear": function (t, i, n) {
            var s = e(t),
                r = this._getInst(s[0]);
            r["selected" + ("M" === n ? "Month" : "Year")] = r["draw" + ("M" === n ? "Month" : "Year")] = parseInt(i.options[i.selectedIndex].value, 10), this._notifyChange(r), this._adjustDate(s)
        },
        "_selectDay": function (t, i, n, s) {
            var r, a = e(t);
            e(s).hasClass(this._unselectableClass) || this._isDisabledDatepicker(a[0]) || (r = this._getInst(a[0]), r.selectedDay = r.currentDay = e("a", s).html(), r.selectedMonth = r.currentMonth = i, r.selectedYear = r.currentYear = n, this._selectDate(t, this._formatDate(r, r.currentDay, r.currentMonth, r.currentYear)))
        },
        "_clearDate": function (t) {
            var i = e(t);
            this._selectDate(i, "")
        },
        "_selectDate": function (t, i) {
            var n, s = e(t),
                r = this._getInst(s[0]);
            i = null != i ? i : this._formatDate(r), r.input && r.input.val(i), this._updateAlternate(r), n = this._get(r, "onSelect"), n ? n.apply(r.input ? r.input[0] : null, [i, r]) : r.input && r.input.trigger("change"), r.inline ? this._updateDatepicker(r) : (this._hideDatepicker(), this._lastInput = r.input[0], "object" != typeof r.input[0] && r.input.focus(), this._lastInput = null)
        },
        "_updateAlternate": function (t) {
            var i, n, s, r = this._get(t, "altField");
            r && (i = this._get(t, "altFormat") || this._get(t, "dateFormat"), n = this._getDate(t), s = this.formatDate(i, n, this._getFormatConfig(t)), e(r).each(function () {
                e(this).val(s)
            }))
        },
        "noWeekends": function (e) {
            var t = e.getDay();
            return [t > 0 && 6 > t, ""]
        },
        "iso8601Week": function (e) {
            var t, i = new Date(e.getTime());
            return i.setDate(i.getDate() + 4 - (i.getDay() || 7)), t = i.getTime(), i.setMonth(0), i.setDate(1), Math.floor(Math.round((t - i) / 864e5) / 7) + 1
        },
        "parseDate": function (t, i, n) {
            if (null == t || null == i) throw "Invalid arguments";
            if (i = "object" == typeof i ? i.toString() : i + "", "" === i) return null;
            var s, r, a, o, l = 0,
                c = (n ? n.shortYearCutoff : null) || this._defaults.shortYearCutoff,
                u = "string" != typeof c ? c : (new Date).getFullYear() % 100 + parseInt(c, 10),
                h = (n ? n.dayNamesShort : null) || this._defaults.dayNamesShort,
                d = (n ? n.dayNames : null) || this._defaults.dayNames,
                p = (n ? n.monthNamesShort : null) || this._defaults.monthNamesShort,
                f = (n ? n.monthNames : null) || this._defaults.monthNames,
                m = -1,
                g = -1,
                v = -1,
                y = -1,
                b = !1,
                w = function (e) {
                    var i = s + 1 < t.length && t.charAt(s + 1) === e;
                    return i && s++, i
                },
                x = function (e) {
                    var t = w(e),
                        n = "@" === e ? 14 : "!" === e ? 20 : "y" === e && t ? 4 : "o" === e ? 3 : 2,
                        s = "y" === e ? n : 1,
                        r = new RegExp("^\\d{" + s + "," + n + "}"),
                        a = i.substring(l).match(r);
                    if (!a) throw "Missing number at position " + l;
                    return l += a[0].length, parseInt(a[0], 10)
                },
                k = function (t, n, s) {
                    var r = -1,
                        a = e.map(w(t) ? s : n, function (e, t) {
                            return [
                                [t, e]
                            ]
                        }).sort(function (e, t) {
                            return -(e[1].length - t[1].length)
                        });
                    if (e.each(a, function (e, t) {
                            var n = t[1];
                            return i.substr(l, n.length).toLowerCase() === n.toLowerCase() ? (r = t[0], l += n.length, !1) : void 0
                        }), -1 !== r) return r + 1;
                    throw "Unknown name at position " + l
                },
                _ = function () {
                    if (i.charAt(l) !== t.charAt(s)) throw "Unexpected literal at position " + l;
                    l++
                };
            for (s = 0; s < t.length; s++)
                if (b) "'" !== t.charAt(s) || w("'") ? _() : b = !1;
                else switch (t.charAt(s)) {
                    case "d":
                        v = x("d");
                        break;
                    case "D":
                        k("D", h, d);
                        break;
                    case "o":
                        y = x("o");
                        break;
                    case "m":
                        g = x("m");
                        break;
                    case "M":
                        g = k("M", p, f);
                        break;
                    case "y":
                        m = x("y");
                        break;
                    case "@":
                        o = new Date(x("@")), m = o.getFullYear(), g = o.getMonth() + 1, v = o.getDate();
                        break;
                    case "!":
                        o = new Date((x("!") - this._ticksTo1970) / 1e4), m = o.getFullYear(), g = o.getMonth() + 1, v = o.getDate();
                        break;
                    case "'":
                        w("'") ? _() : b = !0;
                        break;
                    default:
                        _()
                }
            if (l < i.length && (a = i.substr(l), !/^\s+/.test(a))) throw "Extra/unparsed characters found in date: " + a;
            if (-1 === m ? m = (new Date).getFullYear() : 100 > m && (m += (new Date).getFullYear() - (new Date).getFullYear() % 100 + (u >= m ? 0 : -100)), y > -1)
                for (g = 1, v = y;;) {
                    if (r = this._getDaysInMonth(m, g - 1), r >= v) break;
                    g++, v -= r
                }
            if (o = this._daylightSavingAdjust(new Date(m, g - 1, v)), o.getFullYear() !== m || o.getMonth() + 1 !== g || o.getDate() !== v) throw "Invalid date";
            return o
        },
        "ATOM": "yy-mm-dd",
        "COOKIE": "D, dd M yy",
        "ISO_8601": "yy-mm-dd",
        "RFC_822": "D, d M y",
        "RFC_850": "DD, dd-M-y",
        "RFC_1036": "D, d M y",
        "RFC_1123": "D, d M yy",
        "RFC_2822": "D, d M yy",
        "RSS": "D, d M y",
        "TICKS": "!",
        "TIMESTAMP": "@",
        "W3C": "yy-mm-dd",
        "_ticksTo1970": 24 * (718685 + Math.floor(492.5) - Math.floor(19.7) + Math.floor(4.925)) * 60 * 60 * 1e7,
        "formatDate": function (e, t, i) {
            if (!t) return "";
            var n, s = (i ? i.dayNamesShort : null) || this._defaults.dayNamesShort,
                r = (i ? i.dayNames : null) || this._defaults.dayNames,
                a = (i ? i.monthNamesShort : null) || this._defaults.monthNamesShort,
                o = (i ? i.monthNames : null) || this._defaults.monthNames,
                l = function (t) {
                    var i = n + 1 < e.length && e.charAt(n + 1) === t;
                    return i && n++, i
                },
                c = function (e, t, i) {
                    var n = "" + t;
                    if (l(e))
                        for (; n.length < i;) n = "0" + n;
                    return n
                },
                u = function (e, t, i, n) {
                    return l(e) ? n[t] : i[t]
                },
                h = "",
                d = !1;
            if (t)
                for (n = 0; n < e.length; n++)
                    if (d) "'" !== e.charAt(n) || l("'") ? h += e.charAt(n) : d = !1;
                    else switch (e.charAt(n)) {
                        case "d":
                            h += c("d", t.getDate(), 2);
                            break;
                        case "D":
                            h += u("D", t.getDay(), s, r);
                            break;
                        case "o":
                            h += c("o", Math.round((new Date(t.getFullYear(), t.getMonth(), t.getDate()).getTime() - new Date(t.getFullYear(), 0, 0).getTime()) / 864e5), 3);
                            break;
                        case "m":
                            h += c("m", t.getMonth() + 1, 2);
                            break;
                        case "M":
                            h += u("M", t.getMonth(), a, o);
                            break;
                        case "y":
                            h += l("y") ? t.getFullYear() : (t.getYear() % 100 < 10 ? "0" : "") + t.getYear() % 100;
                            break;
                        case "@":
                            h += t.getTime();
                            break;
                        case "!":
                            h += 1e4 * t.getTime() + this._ticksTo1970;
                            break;
                        case "'":
                            l("'") ? h += "'" : d = !0;
                            break;
                        default:
                            h += e.charAt(n)
                    }
            return h
        },
        "_possibleChars": function (e) {
            var t, i = "",
                n = !1,
                s = function (i) {
                    var n = t + 1 < e.length && e.charAt(t + 1) === i;
                    return n && t++, n
                };
            for (t = 0; t < e.length; t++)
                if (n) "'" !== e.charAt(t) || s("'") ? i += e.charAt(t) : n = !1;
                else switch (e.charAt(t)) {
                    case "d":
                    case "m":
                    case "y":
                    case "@":
                        i += "0123456789";
                        break;
                    case "D":
                    case "M":
                        return null;
                    case "'":
                        s("'") ? i += "'" : n = !0;
                        break;
                    default:
                        i += e.charAt(t)
                }
            return i
        },
        "_get": function (e, t) {
            return void 0 !== e.settings[t] ? e.settings[t] : this._defaults[t]
        },
        "_setDateFromField": function (e, t) {
            if (e.input.val() !== e.lastVal) {
                var i = this._get(e, "dateFormat"),
                    n = e.lastVal = e.input ? e.input.val() : null,
                    s = this._getDefaultDate(e),
                    r = s,
                    a = this._getFormatConfig(e);
                try {
                    r = this.parseDate(i, n, a) || s
                } catch (o) {
                    n = t ? "" : n
                }
                e.selectedDay = r.getDate(), e.drawMonth = e.selectedMonth = r.getMonth(), e.drawYear = e.selectedYear = r.getFullYear(), e.currentDay = n ? r.getDate() : 0, e.currentMonth = n ? r.getMonth() : 0, e.currentYear = n ? r.getFullYear() : 0, this._adjustInstDate(e)
            }
        },
        "_getDefaultDate": function (e) {
            return this._restrictMinMax(e, this._determineDate(e, this._get(e, "defaultDate"), new Date))
        },
        "_determineDate": function (t, i, n) {
            var s = function (e) {
                    var t = new Date;
                    return t.setDate(t.getDate() + e), t
                },
                r = function (i) {
                    try {
                        return e.datepicker.parseDate(e.datepicker._get(t, "dateFormat"), i, e.datepicker._getFormatConfig(t))
                    } catch (n) {}
                    for (var s = (i.toLowerCase().match(/^c/) ? e.datepicker._getDate(t) : null) || new Date, r = s.getFullYear(), a = s.getMonth(), o = s.getDate(), l = /([+\-]?[0-9]+)\s*(d|D|w|W|m|M|y|Y)?/g, c = l.exec(i); c;) {
                        switch (c[2] || "d") {
                            case "d":
                            case "D":
                                o += parseInt(c[1], 10);
                                break;
                            case "w":
                            case "W":
                                o += 7 * parseInt(c[1], 10);
                                break;
                            case "m":
                            case "M":
                                a += parseInt(c[1], 10), o = Math.min(o, e.datepicker._getDaysInMonth(r, a));
                                break;
                            case "y":
                            case "Y":
                                r += parseInt(c[1], 10), o = Math.min(o, e.datepicker._getDaysInMonth(r, a))
                        }
                        c = l.exec(i)
                    }
                    return new Date(r, a, o)
                },
                a = null == i || "" === i ? n : "string" == typeof i ? r(i) : "number" == typeof i ? isNaN(i) ? n : s(i) : new Date(i.getTime());
            return a = a && "Invalid Date" === a.toString() ? n : a, a && (a.setHours(0), a.setMinutes(0), a.setSeconds(0), a.setMilliseconds(0)), this._daylightSavingAdjust(a)
        },
        "_daylightSavingAdjust": function (e) {
            return e ? (e.setHours(e.getHours() > 12 ? e.getHours() + 2 : 0), e) : null
        },
        "_setDate": function (e, t, i) {
            var n = !t,
                s = e.selectedMonth,
                r = e.selectedYear,
                a = this._restrictMinMax(e, this._determineDate(e, t, new Date));
            e.selectedDay = e.currentDay = a.getDate(), e.drawMonth = e.selectedMonth = e.currentMonth = a.getMonth(), e.drawYear = e.selectedYear = e.currentYear = a.getFullYear(), s === e.selectedMonth && r === e.selectedYear || i || this._notifyChange(e), this._adjustInstDate(e), e.input && e.input.val(n ? "" : this._formatDate(e))
        },
        "_getDate": function (e) {
            var t = !e.currentYear || e.input && "" === e.input.val() ? null : this._daylightSavingAdjust(new Date(e.currentYear, e.currentMonth, e.currentDay));
            return t
        },
        "_attachHandlers": function (t) {
            var i = this._get(t, "stepMonths"),
                n = "#" + t.id.replace(/\\\\/g, "\\");
            t.dpDiv.find("[data-handler]").map(function () {
                var t = {
                    "prev": function () {
                        e.datepicker._adjustDate(n, -i, "M")
                    },
                    "next": function () {
                        e.datepicker._adjustDate(n, +i, "M")
                    },
                    "hide": function () {
                        e.datepicker._hideDatepicker()
                    },
                    "today": function () {
                        e.datepicker._gotoToday(n)
                    },
                    "selectDay": function () {
                        return e.datepicker._selectDay(n, +this.getAttribute("data-month"), +this.getAttribute("data-year"), this), !1
                    },
                    "selectMonth": function () {
                        return e.datepicker._selectMonthYear(n, this, "M"), !1
                    },
                    "selectYear": function () {
                        return e.datepicker._selectMonthYear(n, this, "Y"), !1
                    }
                };
                e(this).bind(this.getAttribute("data-event"), t[this.getAttribute("data-handler")])
            })
        },
        "_generateHTML": function (e) {
            var t, i, n, s, r, a, o, l, c, u, h, d, p, f, m, g, v, y, b, w, x, k, _, C, D, S, T, E, M, N, A, I, j, O, F, L, P, H, R, W = new Date,
                q = this._daylightSavingAdjust(new Date(W.getFullYear(), W.getMonth(), W.getDate())),
                z = this._get(e, "isRTL"),
                B = this._get(e, "showButtonPanel"),
                Y = this._get(e, "hideIfNoPrevNext"),
                U = this._get(e, "navigationAsDateFormat"),
                K = this._getNumberOfMonths(e),
                $ = this._get(e, "showCurrentAtPos"),
                V = this._get(e, "stepMonths"),
                X = 1 !== K[0] || 1 !== K[1],
                G = this._daylightSavingAdjust(e.currentDay ? new Date(e.currentYear, e.currentMonth, e.currentDay) : new Date(9999, 9, 9)),
                J = this._getMinMaxDate(e, "min"),
                Q = this._getMinMaxDate(e, "max"),
                Z = e.drawMonth - $,
                et = e.drawYear;
            if (0 > Z && (Z += 12, et--), Q)
                for (t = this._daylightSavingAdjust(new Date(Q.getFullYear(), Q.getMonth() - K[0] * K[1] + 1, Q.getDate())), t = J && J > t ? J : t; this._daylightSavingAdjust(new Date(et, Z, 1)) > t;) Z--, 0 > Z && (Z = 11, et--);
            for (e.drawMonth = Z, e.drawYear = et, i = this._get(e, "prevText"), i = U ? this.formatDate(i, this._daylightSavingAdjust(new Date(et, Z - V, 1)), this._getFormatConfig(e)) : i, n = this._canAdjustMonth(e, -1, et, Z) ? "<a class='ui-datepicker-prev ui-corner-all' data-handler='prev' data-event='click' title='" + i + "'><span class='ui-icon ui-icon-circle-triangle-" + (z ? "e" : "w") + "'>" + i + "</span></a>" : Y ? "" : "<a class='ui-datepicker-prev ui-corner-all ui-state-disabled' title='" + i + "'><span class='ui-icon ui-icon-circle-triangle-" + (z ? "e" : "w") + "'>" + i + "</span></a>", s = this._get(e, "nextText"), s = U ? this.formatDate(s, this._daylightSavingAdjust(new Date(et, Z + V, 1)), this._getFormatConfig(e)) : s, r = this._canAdjustMonth(e, 1, et, Z) ? "<a class='ui-datepicker-next ui-corner-all' data-handler='next' data-event='click' title='" + s + "'><span class='ui-icon ui-icon-circle-triangle-" + (z ? "w" : "e") + "'>" + s + "</span></a>" : Y ? "" : "<a class='ui-datepicker-next ui-corner-all ui-state-disabled' title='" + s + "'><span class='ui-icon ui-icon-circle-triangle-" + (z ? "w" : "e") + "'>" + s + "</span></a>", a = this._get(e, "currentText"), o = this._get(e, "gotoCurrent") && e.currentDay ? G : q, a = U ? this.formatDate(a, o, this._getFormatConfig(e)) : a, l = e.inline ? "" : "<button type='button' class='ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all' data-handler='hide' data-event='click'>" + this._get(e, "closeText") + "</button>", c = B ? "<div class='ui-datepicker-buttonpane ui-widget-content'>" + (z ? l : "") + (this._isInRange(e, o) ? "<button type='button' class='ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all' data-handler='today' data-event='click'>" + a + "</button>" : "") + (z ? "" : l) + "</div>" : "", u = parseInt(this._get(e, "firstDay"), 10), u = isNaN(u) ? 0 : u, h = this._get(e, "showWeek"), d = this._get(e, "dayNames"), p = this._get(e, "dayNamesMin"), f = this._get(e, "monthNames"), m = this._get(e, "monthNamesShort"), g = this._get(e, "beforeShowDay"), v = this._get(e, "showOtherMonths"), y = this._get(e, "selectOtherMonths"), b = this._getDefaultDate(e), w = "", k = 0; k < K[0]; k++) {
                for (_ = "", this.maxRows = 4, C = 0; C < K[1]; C++) {
                    if (D = this._daylightSavingAdjust(new Date(et, Z, e.selectedDay)), S = " ui-corner-all", T = "", X) {
                        if (T += "<div class='ui-datepicker-group", K[1] > 1) switch (C) {
                            case 0:
                                T += " ui-datepicker-group-first", S = " ui-corner-" + (z ? "right" : "left");
                                break;
                            case K[1] - 1:
                                T += " ui-datepicker-group-last", S = " ui-corner-" + (z ? "left" : "right");
                                break;
                            default:
                                T += " ui-datepicker-group-middle", S = ""
                        }
                        T += "'>"
                    }
                    for (T += "<div class='ui-datepicker-header ui-widget-header ui-helper-clearfix" + S + "'>" + (/all|left/.test(S) && 0 === k ? z ? r : n : "") + (/all|right/.test(S) && 0 === k ? z ? n : r : "") + this._generateMonthYearHeader(e, Z, et, J, Q, k > 0 || C > 0, f, m) + "</div><table class='ui-datepicker-calendar'><thead><tr>", E = h ? "<th class='ui-datepicker-week-col'>" + this._get(e, "weekHeader") + "</th>" : "", x = 0; 7 > x; x++) M = (x + u) % 7, E += "<th scope='col'" + ((x + u + 6) % 7 >= 5 ? " class='ui-datepicker-week-end'" : "") + "><span title='" + d[M] + "'>" + p[M] + "</span></th>";
                    for (T += E + "</tr></thead><tbody>", N = this._getDaysInMonth(et, Z), et === e.selectedYear && Z === e.selectedMonth && (e.selectedDay = Math.min(e.selectedDay, N)), A = (this._getFirstDayOfMonth(et, Z) - u + 7) % 7, I = Math.ceil((A + N) / 7), j = X && this.maxRows > I ? this.maxRows : I, this.maxRows = j, O = this._daylightSavingAdjust(new Date(et, Z, 1 - A)), F = 0; j > F; F++) {
                        for (T += "<tr>", L = h ? "<td class='ui-datepicker-week-col'>" + this._get(e, "calculateWeek")(O) + "</td>" : "", x = 0; 7 > x; x++) P = g ? g.apply(e.input ? e.input[0] : null, [O]) : [!0, ""], H = O.getMonth() !== Z, R = H && !y || !P[0] || J && J > O || Q && O > Q, L += "<td class='" + ((x + u + 6) % 7 >= 5 ? " ui-datepicker-week-end" : "") + (H ? " ui-datepicker-other-month" : "") + (O.getTime() === D.getTime() && Z === e.selectedMonth && e._keyEvent || b.getTime() === O.getTime() && b.getTime() === D.getTime() ? " " + this._dayOverClass : "") + (R ? " " + this._unselectableClass + " ui-state-disabled" : "") + (H && !v ? "" : " " + P[1] + (O.getTime() === G.getTime() ? " " + this._currentClass : "") + (O.getTime() === q.getTime() ? " ui-datepicker-today" : "")) + "'" + (H && !v || !P[2] ? "" : " title='" + P[2].replace(/'/g, "&#39;") + "'") + (R ? "" : " data-handler='selectDay' data-event='click' data-month='" + O.getMonth() + "' data-year='" + O.getFullYear() + "'") + ">" + (H && !v ? "&#xa0;" : R ? "<span class='ui-state-default'>" + O.getDate() + "</span>" : "<a class='ui-state-default" + (O.getTime() === q.getTime() ? " ui-state-highlight" : "") + (O.getTime() === G.getTime() ? " ui-state-active" : "") + (H ? " ui-priority-secondary" : "") + "' href='#'>" + O.getDate() + "</a>") + "</td>", O.setDate(O.getDate() + 1), O = this._daylightSavingAdjust(O);
                        T += L + "</tr>"
                    }
                    Z++, Z > 11 && (Z = 0, et++), T += "</tbody></table>" + (X ? "</div>" + (K[0] > 0 && C === K[1] - 1 ? "<div class='ui-datepicker-row-break'></div>" : "") : ""), _ += T
                }
                w += _
            }
            return w += c, e._keyEvent = !1, w
        },
        "_generateMonthYearHeader": function (e, t, i, n, s, r, a, o) {
            var l, c, u, h, d, p, f, m, g = this._get(e, "changeMonth"),
                v = this._get(e, "changeYear"),
                y = this._get(e, "showMonthAfterYear"),
                b = "<div class='ui-datepicker-title'>",
                w = "";
            if (r || !g) w += "<span class='ui-datepicker-month'>" + a[t] + "</span>";
            else {
                for (l = n && n.getFullYear() === i, c = s && s.getFullYear() === i, w += "<select class='ui-datepicker-month' data-handler='selectMonth' data-event='change'>", u = 0; 12 > u; u++)(!l || u >= n.getMonth()) && (!c || u <= s.getMonth()) && (w += "<option value='" + u + "'" + (u === t ? " selected='selected'" : "") + ">" + o[u] + "</option>");
                w += "</select>"
            }
            if (y || (b += w + (!r && g && v ? "" : "&#xa0;")), !e.yearshtml)
                if (e.yearshtml = "", r || !v) b += "<span class='ui-datepicker-year'>" + i + "</span>";
                else {
                    for (h = this._get(e, "yearRange").split(":"), d = (new Date).getFullYear(), p = function (e) {
                            var t = e.match(/c[+\-].*/) ? i + parseInt(e.substring(1), 10) : e.match(/[+\-].*/) ? d + parseInt(e, 10) : parseInt(e, 10);
                            return isNaN(t) ? d : t
                        }, f = p(h[0]), m = Math.max(f, p(h[1] || "")), f = n ? Math.max(f, n.getFullYear()) : f, m = s ? Math.min(m, s.getFullYear()) : m, e.yearshtml += "<select class='ui-datepicker-year' data-handler='selectYear' data-event='change'>"; m >= f; f++) e.yearshtml += "<option value='" + f + "'" + (f === i ? " selected='selected'" : "") + ">" + f + "</option>";
                    e.yearshtml += "</select>", b += e.yearshtml, e.yearshtml = null
                }
            return b += this._get(e, "yearSuffix"), y && (b += (!r && g && v ? "" : "&#xa0;") + w), b += "</div>"
        },
        "_adjustInstDate": function (e, t, i) {
            var n = e.drawYear + ("Y" === i ? t : 0),
                s = e.drawMonth + ("M" === i ? t : 0),
                r = Math.min(e.selectedDay, this._getDaysInMonth(n, s)) + ("D" === i ? t : 0),
                a = this._restrictMinMax(e, this._daylightSavingAdjust(new Date(n, s, r)));
            e.selectedDay = a.getDate(), e.drawMonth = e.selectedMonth = a.getMonth(), e.drawYear = e.selectedYear = a.getFullYear(), ("M" === i || "Y" === i) && this._notifyChange(e)
        },
        "_restrictMinMax": function (e, t) {
            var i = this._getMinMaxDate(e, "min"),
                n = this._getMinMaxDate(e, "max"),
                s = i && i > t ? i : t;
            return n && s > n ? n : s
        },
        "_notifyChange": function (e) {
            var t = this._get(e, "onChangeMonthYear");
            t && t.apply(e.input ? e.input[0] : null, [e.selectedYear, e.selectedMonth + 1, e])
        },
        "_getNumberOfMonths": function (e) {
            var t = this._get(e, "numberOfMonths");
            return null == t ? [1, 1] : "number" == typeof t ? [1, t] : t
        },
        "_getMinMaxDate": function (e, t) {
            return this._determineDate(e, this._get(e, t + "Date"), null)
        },
        "_getDaysInMonth": function (e, t) {
            return 32 - this._daylightSavingAdjust(new Date(e, t, 32)).getDate()
        },
        "_getFirstDayOfMonth": function (e, t) {
            return new Date(e, t, 1).getDay()
        },
        "_canAdjustMonth": function (e, t, i, n) {
            var s = this._getNumberOfMonths(e),
                r = this._daylightSavingAdjust(new Date(i, n + (0 > t ? t : s[0] * s[1]), 1));
            return 0 > t && r.setDate(this._getDaysInMonth(r.getFullYear(), r.getMonth())), this._isInRange(e, r)
        },
        "_isInRange": function (e, t) {
            var i, n, s = this._getMinMaxDate(e, "min"),
                r = this._getMinMaxDate(e, "max"),
                a = null,
                o = null,
                l = this._get(e, "yearRange");
            return l && (i = l.split(":"), n = (new Date).getFullYear(), a = parseInt(i[0], 10), o = parseInt(i[1], 10), i[0].match(/[+\-].*/) && (a += n), i[1].match(/[+\-].*/) && (o += n)), (!s || t.getTime() >= s.getTime()) && (!r || t.getTime() <= r.getTime()) && (!a || t.getFullYear() >= a) && (!o || t.getFullYear() <= o)
        },
        "_getFormatConfig": function (e) {
            var t = this._get(e, "shortYearCutoff");
            return t = "string" != typeof t ? t : (new Date).getFullYear() % 100 + parseInt(t, 10), {
                "shortYearCutoff": t,
                "dayNamesShort": this._get(e, "dayNamesShort"),
                "dayNames": this._get(e, "dayNames"),
                "monthNamesShort": this._get(e, "monthNamesShort"),
                "monthNames": this._get(e, "monthNames")
            }
        },
        "_formatDate": function (e, t, i, n) {
            t || (e.currentDay = e.selectedDay, e.currentMonth = e.selectedMonth, e.currentYear = e.selectedYear);
            var s = t ? "object" == typeof t ? t : this._daylightSavingAdjust(new Date(n, i, t)) : this._daylightSavingAdjust(new Date(e.currentYear, e.currentMonth, e.currentDay));
            return this.formatDate(this._get(e, "dateFormat"), s, this._getFormatConfig(e))
        }
    }), e.fn.datepicker = function (t) {
        if (!this.length) return this;
        e.datepicker.initialized || (e(document).mousedown(e.datepicker._checkExternalClick), e.datepicker.initialized = !0), 0 === e("#" + e.datepicker._mainDivId).length && e("body").append(e.datepicker.dpDiv);
        var i = Array.prototype.slice.call(arguments, 1);
        return "string" != typeof t || "isDisabled" !== t && "getDate" !== t && "widget" !== t ? "option" === t && 2 === arguments.length && "string" == typeof arguments[1] ? e.datepicker["_" + t + "Datepicker"].apply(e.datepicker, [this[0]].concat(i)) : this.each(function () {
            "string" == typeof t ? e.datepicker["_" + t + "Datepicker"].apply(e.datepicker, [this].concat(i)) : e.datepicker._attachDatepicker(this, t)
        }) : e.datepicker["_" + t + "Datepicker"].apply(e.datepicker, [this[0]].concat(i))
    }, e.datepicker = new i, e.datepicker.initialized = !1, e.datepicker.uuid = (new Date).getTime(), e.datepicker.version = "1.11.4", e.datepicker
}),
/*
 * jQuery MiniColors: A tiny color picker built on jQuery
 *
 * Copyright Cory LaViska for A Beautiful Site, LLC. (http://www.abeautifulsite.net/)
 *
 * Licensed under the MIT license: http://opensource.org/licenses/MIT
 *
 */
jQuery && function (e) {
        function t(t, i) {
            var n = e('<div class="minicolors" />'),
                s = e.minicolors.defaults;
            t.data("minicolors-initialized") || (i = e.extend(!0, {}, s, i), n.addClass("minicolors-theme-" + i.theme).toggleClass("minicolors-with-opacity", i.opacity), void 0 !== i.position && e.each(i.position.split(" "), function () {
                n.addClass("minicolors-position-" + this)
            }), t.addClass("minicolors-input").data("minicolors-initialized", !1).data("minicolors-settings", i).prop("size", 7).wrap(n).after('<div class="minicolors-panel minicolors-slider-' + i.control + '"><div class="minicolors-slider"><div class="minicolors-picker"></div></div><div class="minicolors-opacity-slider"><div class="minicolors-picker"></div></div><div class="minicolors-grid"><div class="minicolors-grid-inner"></div><div class="minicolors-picker"><div></div></div></div></div>'), i.inline || (t.after('<span class="minicolors-swatch"><span class="minicolors-swatch-color"></span></span>'), t.next(".minicolors-swatch").on("click", function (e) {
                e.preventDefault(), t.focus()
            })), t.parent().find(".minicolors-panel").on("selectstart", function () {
                return !1
            }).end(), i.inline && t.parent().addClass("minicolors-inline"), o(t, !1), t.data("minicolors-initialized", !0))
        }

        function i(e) {
            var t = e.parent();
            e.removeData("minicolors-initialized").removeData("minicolors-settings").removeProp("size").removeClass("minicolors-input"), t.before(e).remove()
        }

        function n(e) {
            var t = e.parent(),
                i = t.find(".minicolors-panel"),
                n = e.data("minicolors-settings");
            !e.data("minicolors-initialized") || e.prop("disabled") || t.hasClass("minicolors-inline") || t.hasClass("minicolors-focus") || (s(), t.addClass("minicolors-focus"), i.stop(!0, !0).fadeIn(n.showSpeed, function () {
                n.show && n.show.call(e.get(0))
            }))
        }

        function s() {
            e(".minicolors-focus").each(function () {
                var t = e(this),
                    i = t.find(".minicolors-input"),
                    n = t.find(".minicolors-panel"),
                    s = i.data("minicolors-settings");
                n.fadeOut(s.hideSpeed, function () {
                    s.hide && s.hide.call(i.get(0)), t.removeClass("minicolors-focus")
                })
            })
        }

        function r(e, t, i) {
            var n, s, r, o, l = e.parents(".minicolors").find(".minicolors-input"),
                c = l.data("minicolors-settings"),
                u = e.find("[class$=-picker]"),
                h = e.offset().left,
                d = e.offset().top,
                p = Math.round(t.pageX - h),
                f = Math.round(t.pageY - d),
                m = i ? c.animationSpeed : 0;
            t.originalEvent.changedTouches && (p = t.originalEvent.changedTouches[0].pageX - h, f = t.originalEvent.changedTouches[0].pageY - d), 0 > p && (p = 0), 0 > f && (f = 0), p > e.width() && (p = e.width()), f > e.height() && (f = e.height()), e.parent().is(".minicolors-slider-wheel") && u.parent().is(".minicolors-grid") && (n = 75 - p, s = 75 - f, r = Math.sqrt(n * n + s * s), o = Math.atan2(s, n), 0 > o && (o += 2 * Math.PI), r > 75 && (r = 75, p = 75 - 75 * Math.cos(o), f = 75 - 75 * Math.sin(o)), p = Math.round(p), f = Math.round(f)), e.is(".minicolors-grid") ? u.stop(!0).animate({
                "top": f + "px",
                "left": p + "px"
            }, m, c.animationEasing, function () {
                a(l, e)
            }) : u.stop(!0).animate({
                "top": f + "px"
            }, m, c.animationEasing, function () {
                a(l, e)
            })
        }

        function a(e, t) {
            function i(e, t) {
                var i, n;
                return e.length && t ? (i = e.offset().left, n = e.offset().top, {
                    "x": i - t.offset().left + e.outerWidth() / 2,
                    "y": n - t.offset().top + e.outerHeight() / 2
                }) : null
            }
            var n, s, r, a, o, c, u, d = e.val(),
                f = e.attr("data-opacity"),
                m = e.parent(),
                v = e.data("minicolors-settings"),
                y = m.find(".minicolors-swatch"),
                b = m.find(".minicolors-grid"),
                w = m.find(".minicolors-slider"),
                x = m.find(".minicolors-opacity-slider"),
                k = b.find("[class$=-picker]"),
                _ = w.find("[class$=-picker]"),
                C = x.find("[class$=-picker]"),
                D = i(k, b),
                S = i(_, w),
                T = i(C, x);
            if (t.is(".minicolors-grid, .minicolors-slider")) {
                switch (v.control) {
                    case "wheel":
                        a = b.width() / 2 - D.x, o = b.height() / 2 - D.y, c = Math.sqrt(a * a + o * o), u = Math.atan2(o, a), 0 > u && (u += 2 * Math.PI), c > 75 && (c = 75, D.x = 69 - 75 * Math.cos(u), D.y = 69 - 75 * Math.sin(u)), s = p(c / .75, 0, 100), n = p(180 * u / Math.PI, 0, 360), r = p(100 - Math.floor(S.y * (100 / w.height())), 0, 100), d = g({
                            "h": n,
                            "s": s,
                            "b": r
                        }), w.css("backgroundColor", g({
                            "h": n,
                            "s": s,
                            "b": 100
                        }));
                        break;
                    case "saturation":
                        n = p(parseInt(D.x * (360 / b.width()), 10), 0, 360), s = p(100 - Math.floor(S.y * (100 / w.height())), 0, 100), r = p(100 - Math.floor(D.y * (100 / b.height())), 0, 100), d = g({
                            "h": n,
                            "s": s,
                            "b": r
                        }), w.css("backgroundColor", g({
                            "h": n,
                            "s": 100,
                            "b": r
                        })), m.find(".minicolors-grid-inner").css("opacity", s / 100);
                        break;
                    case "brightness":
                        n = p(parseInt(D.x * (360 / b.width()), 10), 0, 360), s = p(100 - Math.floor(D.y * (100 / b.height())), 0, 100), r = p(100 - Math.floor(S.y * (100 / w.height())), 0, 100), d = g({
                            "h": n,
                            "s": s,
                            "b": r
                        }), w.css("backgroundColor", g({
                            "h": n,
                            "s": s,
                            "b": 100
                        })), m.find(".minicolors-grid-inner").css("opacity", 1 - r / 100);
                        break;
                    default:
                        n = p(360 - parseInt(S.y * (360 / w.height()), 10), 0, 360), s = p(Math.floor(D.x * (100 / b.width())), 0, 100), r = p(100 - Math.floor(D.y * (100 / b.height())), 0, 100), d = g({
                            "h": n,
                            "s": s,
                            "b": r
                        }), b.css("backgroundColor", g({
                            "h": n,
                            "s": 100,
                            "b": 100
                        }))
                }
                e.val(h(d, v.letterCase))
            }
            t.is(".minicolors-opacity-slider") && (f = v.opacity ? parseFloat(1 - T.y / x.height()).toFixed(2) : 1, v.opacity && e.attr("data-opacity", f)), y.find("SPAN").css({
                "backgroundColor": d,
                "opacity": f
            }), l(e, d, f)
        }

        function o(e, t) {
            var i, n, s, r, a, o, c, u = e.parent(),
                f = e.data("minicolors-settings"),
                m = u.find(".minicolors-swatch"),
                y = u.find(".minicolors-grid"),
                b = u.find(".minicolors-slider"),
                w = u.find(".minicolors-opacity-slider"),
                x = y.find("[class$=-picker]"),
                k = b.find("[class$=-picker]"),
                _ = w.find("[class$=-picker]");
            switch (i = h(d(e.val(), !0), f.letterCase), i || (i = h(d(f.defaultValue, !0), f.letterCase)), n = v(i), t || e.val(i), f.opacity && (s = "" === e.attr("data-opacity") ? 1 : p(parseFloat(e.attr("data-opacity")).toFixed(2), 0, 1), isNaN(s) && (s = 1), e.attr("data-opacity", s), m.find("SPAN").css("opacity", s), a = p(w.height() - w.height() * s, 0, w.height()), _.css("top", a + "px")), m.find("SPAN").css("backgroundColor", i), f.control) {
                case "wheel":
                    o = p(Math.ceil(.75 * n.s), 0, y.height() / 2), c = n.h * Math.PI / 180, r = p(75 - Math.cos(c) * o, 0, y.width()), a = p(75 - Math.sin(c) * o, 0, y.height()), x.css({
                        "top": a + "px",
                        "left": r + "px"
                    }), a = 150 - n.b / (100 / y.height()), "" === i && (a = 0), k.css("top", a + "px"), b.css("backgroundColor", g({
                        "h": n.h,
                        "s": n.s,
                        "b": 100
                    }));
                    break;
                case "saturation":
                    r = p(5 * n.h / 12, 0, 150), a = p(y.height() - Math.ceil(n.b / (100 / y.height())), 0, y.height()), x.css({
                        "top": a + "px",
                        "left": r + "px"
                    }), a = p(b.height() - n.s * (b.height() / 100), 0, b.height()), k.css("top", a + "px"), b.css("backgroundColor", g({
                        "h": n.h,
                        "s": 100,
                        "b": n.b
                    })), u.find(".minicolors-grid-inner").css("opacity", n.s / 100);
                    break;
                case "brightness":
                    r = p(5 * n.h / 12, 0, 150), a = p(y.height() - Math.ceil(n.s / (100 / y.height())), 0, y.height()), x.css({
                        "top": a + "px",
                        "left": r + "px"
                    }), a = p(b.height() - n.b * (b.height() / 100), 0, b.height()), k.css("top", a + "px"), b.css("backgroundColor", g({
                        "h": n.h,
                        "s": n.s,
                        "b": 100
                    })), u.find(".minicolors-grid-inner").css("opacity", 1 - n.b / 100);
                    break;
                default:
                    r = p(Math.ceil(n.s / (100 / y.width())), 0, y.width()), a = p(y.height() - Math.ceil(n.b / (100 / y.height())), 0, y.height()), x.css({
                        "top": a + "px",
                        "left": r + "px"
                    }), a = p(b.height() - n.h / (360 / b.height()), 0, b.height()), k.css("top", a + "px"), y.css("backgroundColor", g({
                        "h": n.h,
                        "s": 100,
                        "b": 100
                    }))
            }
            e.data("minicolors-initialized") && l(e, i, s)
        }

        function l(e, t, i) {
            var n = e.data("minicolors-settings"),
                s = e.data("minicolors-lastChange");
            s && s.hex === t && s.opacity === i || (e.data("minicolors-lastChange", {
                "hex": t,
                "opacity": i
            }), n.change && (n.changeDelay ? (clearTimeout(e.data("minicolors-changeTimeout")), e.data("minicolors-changeTimeout", setTimeout(function () {
                n.change.call(e.get(0), t, i)
            }, n.changeDelay))) : n.change.call(e.get(0), t, i)), e.trigger("change").trigger("input"))
        }

        function c(t) {
            var i = d(e(t).val(), !0),
                n = b(i),
                s = e(t).attr("data-opacity");
            return n ? (void 0 !== s && e.extend(n, {
                "a": parseFloat(s)
            }), n) : null
        }

        function u(t, i) {
            var n = d(e(t).val(), !0),
                s = b(n),
                r = e(t).attr("data-opacity");
            return s ? (void 0 === r && (r = 1), i ? "rgba(" + s.r + ", " + s.g + ", " + s.b + ", " + parseFloat(r) + ")" : "rgb(" + s.r + ", " + s.g + ", " + s.b + ")") : null
        }

        function h(e, t) {
            return "uppercase" === t ? e.toUpperCase() : e.toLowerCase()
        }

        function d(e, t) {
            return e = e.replace(/[^A-F0-9]/gi, ""), 3 !== e.length && 6 !== e.length ? "" : (3 === e.length && t && (e = e[0] + e[0] + e[1] + e[1] + e[2] + e[2]), "#" + e)
        }

        function p(e, t, i) {
            return t > e && (e = t), e > i && (e = i), e
        }

        function f(e) {
            var t = {},
                i = Math.round(e.h),
                n = Math.round(255 * e.s / 100),
                s = Math.round(255 * e.b / 100);
            if (0 === n) t.r = t.g = t.b = s;
            else {
                var r = s,
                    a = (255 - n) * s / 255,
                    o = (r - a) * (i % 60) / 60;
                360 === i && (i = 0), 60 > i ? (t.r = r, t.b = a, t.g = a + o) : 120 > i ? (t.g = r, t.b = a, t.r = r - o) : 180 > i ? (t.g = r, t.r = a, t.b = a + o) : 240 > i ? (t.b = r, t.r = a, t.g = r - o) : 300 > i ? (t.b = r, t.g = a, t.r = a + o) : 360 > i ? (t.r = r, t.g = a, t.b = r - o) : (t.r = 0, t.g = 0, t.b = 0)
            }
            return {
                "r": Math.round(t.r),
                "g": Math.round(t.g),
                "b": Math.round(t.b)
            }
        }

        function m(t) {
            var i = [t.r.toString(16), t.g.toString(16), t.b.toString(16)];
            return e.each(i, function (e, t) {
                1 === t.length && (i[e] = "0" + t)
            }), "#" + i.join("")
        }

        function g(e) {
            return m(f(e))
        }

        function v(e) {
            var t = y(b(e));
            return 0 === t.s && (t.h = 360), t
        }

        function y(e) {
            var t = {
                    "h": 0,
                    "s": 0,
                    "b": 0
                },
                i = Math.min(e.r, e.g, e.b),
                n = Math.max(e.r, e.g, e.b),
                s = n - i;
            return t.b = n, t.s = 0 !== n ? 255 * s / n : 0, t.h = 0 !== t.s ? e.r === n ? (e.g - e.b) / s : e.g === n ? 2 + (e.b - e.r) / s : 4 + (e.r - e.g) / s : -1, t.h *= 60, t.h < 0 && (t.h += 360), t.s *= 100 / 255, t.b *= 100 / 255, t
        }

        function b(e) {
            return e = parseInt(e.indexOf("#") > -1 ? e.substring(1) : e, 16), {
                "r": e >> 16,
                "g": (65280 & e) >> 8,
                "b": 255 & e
            }
        }
        e.minicolors = {
            "defaults": {
                "animationSpeed": 50,
                "animationEasing": "swing",
                "change": null,
                "changeDelay": 0,
                "control": "hue",
                "defaultValue": "",
                "hide": null,
                "hideSpeed": 100,
                "inline": !1,
                "letterCase": "lowercase",
                "opacity": !1,
                "position": "bottom left",
                "show": null,
                "showSpeed": 100,
                "theme": "default"
            }
        }, e.extend(e.fn, {
            "minicolors": function (r, a) {
                switch (r) {
                    case "destroy":
                        return e(this).each(function () {
                            i(e(this))
                        }), e(this);
                    case "hide":
                        return s(), e(this);
                    case "opacity":
                        return void 0 === a ? e(this).attr("data-opacity") : (e(this).each(function () {
                            o(e(this).attr("data-opacity", a))
                        }), e(this));
                    case "rgbObject":
                        return c(e(this), "rgbaObject" === r);
                    case "rgbString":
                    case "rgbaString":
                        return u(e(this), "rgbaString" === r);
                    case "settings":
                        return void 0 === a ? e(this).data("minicolors-settings") : (e(this).each(function () {
                            var t = e(this).data("minicolors-settings") || {};
                            i(e(this)), e(this).minicolors(e.extend(!0, t, a))
                        }), e(this));
                    case "show":
                        return n(e(this).eq(0)), e(this);
                    case "value":
                        return void 0 === a ? e(this).val() : (e(this).each(function () {
                            o(e(this).val(a))
                        }), e(this));
                    default:
                        return "create" !== r && (a = r), e(this).each(function () {
                            t(e(this), a)
                        }), e(this)
                }
            }
        }), e(document).on("mousedown.minicolors touchstart.minicolors", function (t) {
            e(t.target).parents().add(t.target).hasClass("minicolors") || s()
        }).on("mousedown.minicolors touchstart.minicolors", ".minicolors-grid, .minicolors-slider, .minicolors-opacity-slider", function (t) {
            var i = e(this);
            t.preventDefault(), e(document).data("minicolors-target", i), r(i, t, !0)
        }).on("mousemove.minicolors touchmove.minicolors", function (t) {
            var i = e(document).data("minicolors-target");
            i && r(i, t)
        }).on("mouseup.minicolors touchend.minicolors", function () {
            e(this).removeData("minicolors-target")
        }).on("mousedown.minicolors touchstart.minicolors", ".minicolors-swatch", function (t) {
            var i = e(this).parent().find(".minicolors-input");
            t.preventDefault(), n(i)
        }).on("focus.minicolors", ".minicolors-input", function () {
            var t = e(this);
            t.data("minicolors-initialized") && n(t)
        }).on("blur.minicolors", ".minicolors-input", function () {
            var t = e(this),
                i = t.data("minicolors-settings");
            t.data("minicolors-initialized") && (t.val(d(t.val(), !0)), "" === t.val() && t.val(d(i.defaultValue, !0)), t.val(h(t.val(), i.letterCase)))
        }).on("keydown.minicolors", ".minicolors-input", function (t) {
            var i = e(this);
            if (i.data("minicolors-initialized")) switch (t.keyCode) {
                case 9:
                    s();
                    break;
                case 13:
                case 27:
                    s(), i.blur()
            }
        }).on("keyup.minicolors", ".minicolors-input", function () {
            var t = e(this);
            t.data("minicolors-initialized") && o(t, !0)
        }).on("paste.minicolors", ".minicolors-input", function () {
            var t = e(this);
            t.data("minicolors-initialized") && setTimeout(function () {
                o(t, !0)
            }, 1)
        })
    }(jQuery),
    /*
    Copyright 2012 Igor Vaynberg

    Version: 3.5.2 Timestamp: Sat Nov  1 14:43:36 EDT 2014

    This software is licensed under the Apache License, Version 2.0 (the "Apache License") or the GNU
    General Public License version 2 (the "GPL License"). You may choose either license to govern your
    use of this software only upon the condition that you accept all of the terms of either the Apache
    License or the GPL License.

    You may obtain a copy of the Apache License and the GPL License at:

        http://www.apache.org/licenses/LICENSE-2.0
        http://www.gnu.org/licenses/gpl-2.0.html

    Unless required by applicable law or agreed to in writing, software distributed under the
    Apache License or the GPL License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR
    CONDITIONS OF ANY KIND, either express or implied. See the Apache License and the GPL License for
    the specific language governing permissions and limitations under the Apache License and the GPL License.
    */
    function (e) {
        "undefined" == typeof e.fn.each2 && e.extend(e.fn, {
            "each2": function (t) {
                for (var i = e([0]), n = -1, s = this.length; ++n < s && (i.context = i[0] = this[n]) && t.call(i[0], n, i) !== !1;);
                return this
            }
        })
    }(jQuery),
    function (e, t) {
        "use strict";

        function i(t) {
            var i = e(document.createTextNode(""));
            t.before(i), i.before(t), i.remove()
        }

        function n(e) {
            function t(e) {
                return W[e] || e
            }
            return e.replace(/[^\u0000-\u007E]/g, t)
        }

        function s(e, t) {
            for (var i = 0, n = t.length; n > i; i += 1)
                if (a(e, t[i])) return i;
            return -1
        }

        function r() {
            var t = e(R);
            t.appendTo(document.body);
            var i = {
                "width": t.width() - t[0].clientWidth,
                "height": t.height() - t[0].clientHeight
            };
            return t.remove(), i
        }

        function a(e, i) {
            return e === i ? !0 : e === t || i === t ? !1 : null === e || null === i ? !1 : e.constructor === String ? e + "" == i + "" : i.constructor === String ? i + "" == e + "" : !1
        }

        function o(e, t, i) {
            var n, s, r;
            if (null === e || e.length < 1) return [];
            for (n = e.split(t), s = 0, r = n.length; r > s; s += 1) n[s] = i(n[s]);
            return n
        }

        function l(e) {
            return e.outerWidth(!1) - e.width()
        }

        function c(i) {
            var n = "keyup-change-value";
            i.on("keydown", function () {
                e.data(i, n) === t && e.data(i, n, i.val())
            }), i.on("keyup", function () {
                var s = e.data(i, n);
                s !== t && i.val() !== s && (e.removeData(i, n), i.trigger("keyup-change"))
            })
        }

        function u(i) {
            i.on("mousemove", function (i) {
                var n = P;
                (n === t || n.x !== i.pageX || n.y !== i.pageY) && e(i.target).trigger("mousemove-filtered", i)
            })
        }

        function h(e, i, n) {
            n = n || t;
            var s;
            return function () {
                var t = arguments;
                window.clearTimeout(s), s = window.setTimeout(function () {
                    i.apply(n, t)
                }, e)
            }
        }

        function d(e, t) {
            var i = h(e, function (e) {
                t.trigger("scroll-debounced", e)
            });
            t.on("scroll", function (e) {
                s(e.target, t.get()) >= 0 && i(e)
            })
        }

        function p(e) {
            e[0] !== document.activeElement && window.setTimeout(function () {
                var t, i = e[0],
                    n = e.val().length;
                e.focus();
                var s = i.offsetWidth > 0 || i.offsetHeight > 0;
                s && i === document.activeElement && (i.setSelectionRange ? i.setSelectionRange(n, n) : i.createTextRange && (t = i.createTextRange(), t.collapse(!1), t.select()))
            }, 0)
        }

        function f(t) {
            t = e(t)[0];
            var i = 0,
                n = 0;
            if ("selectionStart" in t) i = t.selectionStart, n = t.selectionEnd - i;
            else if ("selection" in document) {
                t.focus();
                var s = document.selection.createRange();
                n = document.selection.createRange().text.length, s.moveStart("character", -t.value.length), i = s.text.length - n
            }
            return {
                "offset": i,
                "length": n
            }
        }

        function m(e) {
            e.preventDefault(), e.stopPropagation()
        }

        function g(e) {
            e.preventDefault(), e.stopImmediatePropagation()
        }

        function v(t) {
            if (!O) {
                var i = t[0].currentStyle || window.getComputedStyle(t[0], null);
                O = e(document.createElement("div")).css({
                    "position": "absolute",
                    "left": "-10000px",
                    "top": "-10000px",
                    "display": "none",
                    "fontSize": i.fontSize,
                    "fontFamily": i.fontFamily,
                    "fontStyle": i.fontStyle,
                    "fontWeight": i.fontWeight,
                    "letterSpacing": i.letterSpacing,
                    "textTransform": i.textTransform,
                    "whiteSpace": "nowrap"
                }), O.attr("class", "select2-sizer"), e(document.body).append(O)
            }
            return O.text(t.val()), O.width()
        }

        function y(t, i, n) {
            var s, r, a = [];
            s = e.trim(t.attr("class")), s && (s = "" + s, e(s.split(/\s+/)).each2(function () {
                0 === this.indexOf("select2-") && a.push(this)
            })), s = e.trim(i.attr("class")), s && (s = "" + s, e(s.split(/\s+/)).each2(function () {
                0 !== this.indexOf("select2-") && (r = n(this), r && a.push(r))
            })), t.attr("class", a.join(" "))
        }

        function b(e, t, i, s) {
            var r = n(e.toUpperCase()).indexOf(n(t.toUpperCase())),
                a = t.length;
            return 0 > r ? void i.push(s(e)) : (i.push(s(e.substring(0, r))), i.push("<span class='select2-match'>"), i.push(s(e.substring(r, r + a))), i.push("</span>"), void i.push(s(e.substring(r + a, e.length))))
        }

        function w(e) {
            var t = {
                "\\": "&#92;",
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#39;",
                "/": "&#47;"
            };
            return String(e).replace(/[&<>"'\/\\]/g, function (e) {
                return t[e]
            })
        }

        function x(i) {
            var n, s = null,
                r = i.quietMillis || 100,
                a = i.url,
                o = this;
            return function (l) {
                window.clearTimeout(n), n = window.setTimeout(function () {
                    var n = i.data,
                        r = a,
                        c = i.transport || e.fn.select2.ajaxDefaults.transport,
                        u = {
                            "type": i.type || "GET",
                            "cache": i.cache || !1,
                            "jsonpCallback": i.jsonpCallback || t,
                            "dataType": i.dataType || "json"
                        },
                        h = e.extend({}, e.fn.select2.ajaxDefaults.params, u);
                    n = n ? n.call(o, l.term, l.page, l.context) : null, r = "function" == typeof r ? r.call(o, l.term, l.page, l.context) : r, s && "function" == typeof s.abort && s.abort(), i.params && (e.isFunction(i.params) ? e.extend(h, i.params.call(o)) : e.extend(h, i.params)), e.extend(h, {
                        "url": r,
                        "dataType": i.dataType,
                        "data": n,
                        "success": function (e) {
                            var t = i.results(e, l.page, l);
                            l.callback(t)
                        },
                        "error": function (e, t, i) {
                            var n = {
                                "hasError": !0,
                                "jqXHR": e,
                                "textStatus": t,
                                "errorThrown": i
                            };
                            l.callback(n)
                        }
                    }), s = c.call(o, h)
                }, r)
            }
        }

        function k(t) {
            var i, n, s = t,
                r = function (e) {
                    return "" + e.text
                };
            e.isArray(s) && (n = s, s = {
                "results": n
            }), e.isFunction(s) === !1 && (n = s, s = function () {
                return n
            });
            var a = s();
            return a.text && (r = a.text, e.isFunction(r) || (i = a.text, r = function (e) {
                    return e[i]
                })),
                function (t) {
                    var i, n = t.term,
                        a = {
                            "results": []
                        };
                    return "" === n ? void t.callback(s()) : (i = function (s, a) {
                        var o, l;
                        if (s = s[0], s.children) {
                            o = {};
                            for (l in s) s.hasOwnProperty(l) && (o[l] = s[l]);
                            o.children = [], e(s.children).each2(function (e, t) {
                                i(t, o.children)
                            }), (o.children.length || t.matcher(n, r(o), s)) && a.push(o)
                        } else t.matcher(n, r(s), s) && a.push(s)
                    }, e(s().results).each2(function (e, t) {
                        i(t, a.results)
                    }), void t.callback(a))
                }
        }

        function _(i) {
            var n = e.isFunction(i);
            return function (s) {
                var r = s.term,
                    a = {
                        "results": []
                    },
                    o = n ? i(s) : i;
                e.isArray(o) && (e(o).each(function () {
                    var e = this.text !== t,
                        i = e ? this.text : this;
                    ("" === r || s.matcher(r, i)) && a.results.push(e ? this : {
                        "id": this,
                        "text": this
                    })
                }), s.callback(a))
            }
        }

        function C(t, i) {
            if (e.isFunction(t)) return !0;
            if (!t) return !1;
            if ("string" == typeof t) return !0;
            throw new Error(i + " must be a string, function, or falsy value")
        }

        function D(t, i) {
            if (e.isFunction(t)) {
                var n = Array.prototype.slice.call(arguments, 2);
                return t.apply(i, n)
            }
            return t
        }

        function S(t) {
            var i = 0;
            return e.each(t, function (e, t) {
                t.children ? i += S(t.children) : i++
            }), i
        }

        function T(e, i, n, s) {
            var r, o, l, c, u, h = e,
                d = !1;
            if (!s.createSearchChoice || !s.tokenSeparators || s.tokenSeparators.length < 1) return t;
            for (;;) {
                for (o = -1, l = 0, c = s.tokenSeparators.length; c > l && (u = s.tokenSeparators[l], o = e.indexOf(u), !(o >= 0)); l++);
                if (0 > o) break;
                if (r = e.substring(0, o), e = e.substring(o + u.length), r.length > 0 && (r = s.createSearchChoice.call(this, r, i), r !== t && null !== r && s.id(r) !== t && null !== s.id(r))) {
                    for (d = !1, l = 0, c = i.length; c > l; l++)
                        if (a(s.id(r), s.id(i[l]))) {
                            d = !0;
                            break
                        }
                    d || n(r)
                }
            }
            return h !== e ? e : void 0
        }

        function E() {
            var t = this;
            e.each(arguments, function (e, i) {
                t[i].remove(), t[i] = null
            })
        }

        function M(t, i) {
            var n = function () {};
            return n.prototype = new t, n.prototype.constructor = n, n.prototype.parent = t.prototype, n.prototype = e.extend(n.prototype, i), n
        }
        if (window.Select2 === t) {
            var N, A, I, j, O, F, L, P = {
                    "x": 0,
                    "y": 0
                },
                H = {
                    "TAB": 9,
                    "ENTER": 13,
                    "ESC": 27,
                    "SPACE": 32,
                    "LEFT": 37,
                    "UP": 38,
                    "RIGHT": 39,
                    "DOWN": 40,
                    "SHIFT": 16,
                    "CTRL": 17,
                    "ALT": 18,
                    "PAGE_UP": 33,
                    "PAGE_DOWN": 34,
                    "HOME": 36,
                    "END": 35,
                    "BACKSPACE": 8,
                    "DELETE": 46,
                    "isArrow": function (e) {
                        switch (e = e.which ? e.which : e) {
                            case H.LEFT:
                            case H.RIGHT:
                            case H.UP:
                            case H.DOWN:
                                return !0
                        }
                        return !1
                    },
                    "isControl": function (e) {
                        var t = e.which;
                        switch (t) {
                            case H.SHIFT:
                            case H.CTRL:
                            case H.ALT:
                                return !0
                        }
                        return e.metaKey ? !0 : !1
                    },
                    "isFunctionKey": function (e) {
                        return e = e.which ? e.which : e, e >= 112 && 123 >= e
                    }
                },
                R = "<div class='select2-measure-scrollbar'></div>",
                W = {
                    "\u24b6": "A",
                    "\uff21": "A",
                    "\xc0": "A",
                    "\xc1": "A",
                    "\xc2": "A",
                    "\u1ea6": "A",
                    "\u1ea4": "A",
                    "\u1eaa": "A",
                    "\u1ea8": "A",
                    "\xc3": "A",
                    "\u0100": "A",
                    "\u0102": "A",
                    "\u1eb0": "A",
                    "\u1eae": "A",
                    "\u1eb4": "A",
                    "\u1eb2": "A",
                    "\u0226": "A",
                    "\u01e0": "A",
                    "\xc4": "A",
                    "\u01de": "A",
                    "\u1ea2": "A",
                    "\xc5": "A",
                    "\u01fa": "A",
                    "\u01cd": "A",
                    "\u0200": "A",
                    "\u0202": "A",
                    "\u1ea0": "A",
                    "\u1eac": "A",
                    "\u1eb6": "A",
                    "\u1e00": "A",
                    "\u0104": "A",
                    "\u023a": "A",
                    "\u2c6f": "A",
                    "\ua732": "AA",
                    "\xc6": "AE",
                    "\u01fc": "AE",
                    "\u01e2": "AE",
                    "\ua734": "AO",
                    "\ua736": "AU",
                    "\ua738": "AV",
                    "\ua73a": "AV",
                    "\ua73c": "AY",
                    "\u24b7": "B",
                    "\uff22": "B",
                    "\u1e02": "B",
                    "\u1e04": "B",
                    "\u1e06": "B",
                    "\u0243": "B",
                    "\u0182": "B",
                    "\u0181": "B",
                    "\u24b8": "C",
                    "\uff23": "C",
                    "\u0106": "C",
                    "\u0108": "C",
                    "\u010a": "C",
                    "\u010c": "C",
                    "\xc7": "C",
                    "\u1e08": "C",
                    "\u0187": "C",
                    "\u023b": "C",
                    "\ua73e": "C",
                    "\u24b9": "D",
                    "\uff24": "D",
                    "\u1e0a": "D",
                    "\u010e": "D",
                    "\u1e0c": "D",
                    "\u1e10": "D",
                    "\u1e12": "D",
                    "\u1e0e": "D",
                    "\u0110": "D",
                    "\u018b": "D",
                    "\u018a": "D",
                    "\u0189": "D",
                    "\ua779": "D",
                    "\u01f1": "DZ",
                    "\u01c4": "DZ",
                    "\u01f2": "Dz",
                    "\u01c5": "Dz",
                    "\u24ba": "E",
                    "\uff25": "E",
                    "\xc8": "E",
                    "\xc9": "E",
                    "\xca": "E",
                    "\u1ec0": "E",
                    "\u1ebe": "E",
                    "\u1ec4": "E",
                    "\u1ec2": "E",
                    "\u1ebc": "E",
                    "\u0112": "E",
                    "\u1e14": "E",
                    "\u1e16": "E",
                    "\u0114": "E",
                    "\u0116": "E",
                    "\xcb": "E",
                    "\u1eba": "E",
                    "\u011a": "E",
                    "\u0204": "E",
                    "\u0206": "E",
                    "\u1eb8": "E",
                    "\u1ec6": "E",
                    "\u0228": "E",
                    "\u1e1c": "E",
                    "\u0118": "E",
                    "\u1e18": "E",
                    "\u1e1a": "E",
                    "\u0190": "E",
                    "\u018e": "E",
                    "\u24bb": "F",
                    "\uff26": "F",
                    "\u1e1e": "F",
                    "\u0191": "F",
                    "\ua77b": "F",
                    "\u24bc": "G",
                    "\uff27": "G",
                    "\u01f4": "G",
                    "\u011c": "G",
                    "\u1e20": "G",
                    "\u011e": "G",
                    "\u0120": "G",
                    "\u01e6": "G",
                    "\u0122": "G",
                    "\u01e4": "G",
                    "\u0193": "G",
                    "\ua7a0": "G",
                    "\ua77d": "G",
                    "\ua77e": "G",
                    "\u24bd": "H",
                    "\uff28": "H",
                    "\u0124": "H",
                    "\u1e22": "H",
                    "\u1e26": "H",
                    "\u021e": "H",
                    "\u1e24": "H",
                    "\u1e28": "H",
                    "\u1e2a": "H",
                    "\u0126": "H",
                    "\u2c67": "H",
                    "\u2c75": "H",
                    "\ua78d": "H",
                    "\u24be": "I",
                    "\uff29": "I",
                    "\xcc": "I",
                    "\xcd": "I",
                    "\xce": "I",
                    "\u0128": "I",
                    "\u012a": "I",
                    "\u012c": "I",
                    "\u0130": "I",
                    "\xcf": "I",
                    "\u1e2e": "I",
                    "\u1ec8": "I",
                    "\u01cf": "I",
                    "\u0208": "I",
                    "\u020a": "I",
                    "\u1eca": "I",
                    "\u012e": "I",
                    "\u1e2c": "I",
                    "\u0197": "I",
                    "\u24bf": "J",
                    "\uff2a": "J",
                    "\u0134": "J",
                    "\u0248": "J",
                    "\u24c0": "K",
                    "\uff2b": "K",
                    "\u1e30": "K",
                    "\u01e8": "K",
                    "\u1e32": "K",
                    "\u0136": "K",
                    "\u1e34": "K",
                    "\u0198": "K",
                    "\u2c69": "K",
                    "\ua740": "K",
                    "\ua742": "K",
                    "\ua744": "K",
                    "\ua7a2": "K",
                    "\u24c1": "L",
                    "\uff2c": "L",
                    "\u013f": "L",
                    "\u0139": "L",
                    "\u013d": "L",
                    "\u1e36": "L",
                    "\u1e38": "L",
                    "\u013b": "L",
                    "\u1e3c": "L",
                    "\u1e3a": "L",
                    "\u0141": "L",
                    "\u023d": "L",
                    "\u2c62": "L",
                    "\u2c60": "L",
                    "\ua748": "L",
                    "\ua746": "L",
                    "\ua780": "L",
                    "\u01c7": "LJ",
                    "\u01c8": "Lj",
                    "\u24c2": "M",
                    "\uff2d": "M",
                    "\u1e3e": "M",
                    "\u1e40": "M",
                    "\u1e42": "M",
                    "\u2c6e": "M",
                    "\u019c": "M",
                    "\u24c3": "N",
                    "\uff2e": "N",
                    "\u01f8": "N",
                    "\u0143": "N",
                    "\xd1": "N",
                    "\u1e44": "N",
                    "\u0147": "N",
                    "\u1e46": "N",
                    "\u0145": "N",
                    "\u1e4a": "N",
                    "\u1e48": "N",
                    "\u0220": "N",
                    "\u019d": "N",
                    "\ua790": "N",
                    "\ua7a4": "N",
                    "\u01ca": "NJ",
                    "\u01cb": "Nj",
                    "\u24c4": "O",
                    "\uff2f": "O",
                    "\xd2": "O",
                    "\xd3": "O",
                    "\xd4": "O",
                    "\u1ed2": "O",
                    "\u1ed0": "O",
                    "\u1ed6": "O",
                    "\u1ed4": "O",
                    "\xd5": "O",
                    "\u1e4c": "O",
                    "\u022c": "O",
                    "\u1e4e": "O",
                    "\u014c": "O",
                    "\u1e50": "O",
                    "\u1e52": "O",
                    "\u014e": "O",
                    "\u022e": "O",
                    "\u0230": "O",
                    "\xd6": "O",
                    "\u022a": "O",
                    "\u1ece": "O",
                    "\u0150": "O",
                    "\u01d1": "O",
                    "\u020c": "O",
                    "\u020e": "O",
                    "\u01a0": "O",
                    "\u1edc": "O",
                    "\u1eda": "O",
                    "\u1ee0": "O",
                    "\u1ede": "O",
                    "\u1ee2": "O",
                    "\u1ecc": "O",
                    "\u1ed8": "O",
                    "\u01ea": "O",
                    "\u01ec": "O",
                    "\xd8": "O",
                    "\u01fe": "O",
                    "\u0186": "O",
                    "\u019f": "O",
                    "\ua74a": "O",
                    "\ua74c": "O",
                    "\u01a2": "OI",
                    "\ua74e": "OO",
                    "\u0222": "OU",
                    "\u24c5": "P",
                    "\uff30": "P",
                    "\u1e54": "P",
                    "\u1e56": "P",
                    "\u01a4": "P",
                    "\u2c63": "P",
                    "\ua750": "P",
                    "\ua752": "P",
                    "\ua754": "P",
                    "\u24c6": "Q",
                    "\uff31": "Q",
                    "\ua756": "Q",
                    "\ua758": "Q",
                    "\u024a": "Q",
                    "\u24c7": "R",
                    "\uff32": "R",
                    "\u0154": "R",
                    "\u1e58": "R",
                    "\u0158": "R",
                    "\u0210": "R",
                    "\u0212": "R",
                    "\u1e5a": "R",
                    "\u1e5c": "R",
                    "\u0156": "R",
                    "\u1e5e": "R",
                    "\u024c": "R",
                    "\u2c64": "R",
                    "\ua75a": "R",
                    "\ua7a6": "R",
                    "\ua782": "R",
                    "\u24c8": "S",
                    "\uff33": "S",
                    "\u1e9e": "S",
                    "\u015a": "S",
                    "\u1e64": "S",
                    "\u015c": "S",
                    "\u1e60": "S",
                    "\u0160": "S",
                    "\u1e66": "S",
                    "\u1e62": "S",
                    "\u1e68": "S",
                    "\u0218": "S",
                    "\u015e": "S",
                    "\u2c7e": "S",
                    "\ua7a8": "S",
                    "\ua784": "S",
                    "\u24c9": "T",
                    "\uff34": "T",
                    "\u1e6a": "T",
                    "\u0164": "T",
                    "\u1e6c": "T",
                    "\u021a": "T",
                    "\u0162": "T",
                    "\u1e70": "T",
                    "\u1e6e": "T",
                    "\u0166": "T",
                    "\u01ac": "T",
                    "\u01ae": "T",
                    "\u023e": "T",
                    "\ua786": "T",
                    "\ua728": "TZ",
                    "\u24ca": "U",
                    "\uff35": "U",
                    "\xd9": "U",
                    "\xda": "U",
                    "\xdb": "U",
                    "\u0168": "U",
                    "\u1e78": "U",
                    "\u016a": "U",
                    "\u1e7a": "U",
                    "\u016c": "U",
                    "\xdc": "U",
                    "\u01db": "U",
                    "\u01d7": "U",
                    "\u01d5": "U",
                    "\u01d9": "U",
                    "\u1ee6": "U",
                    "\u016e": "U",
                    "\u0170": "U",
                    "\u01d3": "U",
                    "\u0214": "U",
                    "\u0216": "U",
                    "\u01af": "U",
                    "\u1eea": "U",
                    "\u1ee8": "U",
                    "\u1eee": "U",
                    "\u1eec": "U",
                    "\u1ef0": "U",
                    "\u1ee4": "U",
                    "\u1e72": "U",
                    "\u0172": "U",
                    "\u1e76": "U",
                    "\u1e74": "U",
                    "\u0244": "U",
                    "\u24cb": "V",
                    "\uff36": "V",
                    "\u1e7c": "V",
                    "\u1e7e": "V",
                    "\u01b2": "V",
                    "\ua75e": "V",
                    "\u0245": "V",
                    "\ua760": "VY",
                    "\u24cc": "W",
                    "\uff37": "W",
                    "\u1e80": "W",
                    "\u1e82": "W",
                    "\u0174": "W",
                    "\u1e86": "W",
                    "\u1e84": "W",
                    "\u1e88": "W",
                    "\u2c72": "W",
                    "\u24cd": "X",
                    "\uff38": "X",
                    "\u1e8a": "X",
                    "\u1e8c": "X",
                    "\u24ce": "Y",
                    "\uff39": "Y",
                    "\u1ef2": "Y",
                    "\xdd": "Y",
                    "\u0176": "Y",
                    "\u1ef8": "Y",
                    "\u0232": "Y",
                    "\u1e8e": "Y",
                    "\u0178": "Y",
                    "\u1ef6": "Y",
                    "\u1ef4": "Y",
                    "\u01b3": "Y",
                    "\u024e": "Y",
                    "\u1efe": "Y",
                    "\u24cf": "Z",
                    "\uff3a": "Z",
                    "\u0179": "Z",
                    "\u1e90": "Z",
                    "\u017b": "Z",
                    "\u017d": "Z",
                    "\u1e92": "Z",
                    "\u1e94": "Z",
                    "\u01b5": "Z",
                    "\u0224": "Z",
                    "\u2c7f": "Z",
                    "\u2c6b": "Z",
                    "\ua762": "Z",
                    "\u24d0": "a",
                    "\uff41": "a",
                    "\u1e9a": "a",
                    "\xe0": "a",
                    "\xe1": "a",
                    "\xe2": "a",
                    "\u1ea7": "a",
                    "\u1ea5": "a",
                    "\u1eab": "a",
                    "\u1ea9": "a",
                    "\xe3": "a",
                    "\u0101": "a",
                    "\u0103": "a",
                    "\u1eb1": "a",
                    "\u1eaf": "a",
                    "\u1eb5": "a",
                    "\u1eb3": "a",
                    "\u0227": "a",
                    "\u01e1": "a",
                    "\xe4": "a",
                    "\u01df": "a",
                    "\u1ea3": "a",
                    "\xe5": "a",
                    "\u01fb": "a",
                    "\u01ce": "a",
                    "\u0201": "a",
                    "\u0203": "a",
                    "\u1ea1": "a",
                    "\u1ead": "a",
                    "\u1eb7": "a",
                    "\u1e01": "a",
                    "\u0105": "a",
                    "\u2c65": "a",
                    "\u0250": "a",
                    "\ua733": "aa",
                    "\xe6": "ae",
                    "\u01fd": "ae",
                    "\u01e3": "ae",
                    "\ua735": "ao",
                    "\ua737": "au",
                    "\ua739": "av",
                    "\ua73b": "av",
                    "\ua73d": "ay",
                    "\u24d1": "b",
                    "\uff42": "b",
                    "\u1e03": "b",
                    "\u1e05": "b",
                    "\u1e07": "b",
                    "\u0180": "b",
                    "\u0183": "b",
                    "\u0253": "b",
                    "\u24d2": "c",
                    "\uff43": "c",
                    "\u0107": "c",
                    "\u0109": "c",
                    "\u010b": "c",
                    "\u010d": "c",
                    "\xe7": "c",
                    "\u1e09": "c",
                    "\u0188": "c",
                    "\u023c": "c",
                    "\ua73f": "c",
                    "\u2184": "c",
                    "\u24d3": "d",
                    "\uff44": "d",
                    "\u1e0b": "d",
                    "\u010f": "d",
                    "\u1e0d": "d",
                    "\u1e11": "d",
                    "\u1e13": "d",
                    "\u1e0f": "d",
                    "\u0111": "d",
                    "\u018c": "d",
                    "\u0256": "d",
                    "\u0257": "d",
                    "\ua77a": "d",
                    "\u01f3": "dz",
                    "\u01c6": "dz",
                    "\u24d4": "e",
                    "\uff45": "e",
                    "\xe8": "e",
                    "\xe9": "e",
                    "\xea": "e",
                    "\u1ec1": "e",
                    "\u1ebf": "e",
                    "\u1ec5": "e",
                    "\u1ec3": "e",
                    "\u1ebd": "e",
                    "\u0113": "e",
                    "\u1e15": "e",
                    "\u1e17": "e",
                    "\u0115": "e",
                    "\u0117": "e",
                    "\xeb": "e",
                    "\u1ebb": "e",
                    "\u011b": "e",
                    "\u0205": "e",
                    "\u0207": "e",
                    "\u1eb9": "e",
                    "\u1ec7": "e",
                    "\u0229": "e",
                    "\u1e1d": "e",
                    "\u0119": "e",
                    "\u1e19": "e",
                    "\u1e1b": "e",
                    "\u0247": "e",
                    "\u025b": "e",
                    "\u01dd": "e",
                    "\u24d5": "f",
                    "\uff46": "f",
                    "\u1e1f": "f",
                    "\u0192": "f",
                    "\ua77c": "f",
                    "\u24d6": "g",
                    "\uff47": "g",
                    "\u01f5": "g",
                    "\u011d": "g",
                    "\u1e21": "g",
                    "\u011f": "g",
                    "\u0121": "g",
                    "\u01e7": "g",
                    "\u0123": "g",
                    "\u01e5": "g",
                    "\u0260": "g",
                    "\ua7a1": "g",
                    "\u1d79": "g",
                    "\ua77f": "g",
                    "\u24d7": "h",
                    "\uff48": "h",
                    "\u0125": "h",
                    "\u1e23": "h",
                    "\u1e27": "h",
                    "\u021f": "h",
                    "\u1e25": "h",
                    "\u1e29": "h",
                    "\u1e2b": "h",
                    "\u1e96": "h",
                    "\u0127": "h",
                    "\u2c68": "h",
                    "\u2c76": "h",
                    "\u0265": "h",
                    "\u0195": "hv",
                    "\u24d8": "i",
                    "\uff49": "i",
                    "\xec": "i",
                    "\xed": "i",
                    "\xee": "i",
                    "\u0129": "i",
                    "\u012b": "i",
                    "\u012d": "i",
                    "\xef": "i",
                    "\u1e2f": "i",
                    "\u1ec9": "i",
                    "\u01d0": "i",
                    "\u0209": "i",
                    "\u020b": "i",
                    "\u1ecb": "i",
                    "\u012f": "i",
                    "\u1e2d": "i",
                    "\u0268": "i",
                    "\u0131": "i",
                    "\u24d9": "j",
                    "\uff4a": "j",
                    "\u0135": "j",
                    "\u01f0": "j",
                    "\u0249": "j",
                    "\u24da": "k",
                    "\uff4b": "k",
                    "\u1e31": "k",
                    "\u01e9": "k",
                    "\u1e33": "k",
                    "\u0137": "k",
                    "\u1e35": "k",
                    "\u0199": "k",
                    "\u2c6a": "k",
                    "\ua741": "k",
                    "\ua743": "k",
                    "\ua745": "k",
                    "\ua7a3": "k",
                    "\u24db": "l",
                    "\uff4c": "l",
                    "\u0140": "l",
                    "\u013a": "l",
                    "\u013e": "l",
                    "\u1e37": "l",
                    "\u1e39": "l",
                    "\u013c": "l",
                    "\u1e3d": "l",
                    "\u1e3b": "l",
                    "\u017f": "l",
                    "\u0142": "l",
                    "\u019a": "l",
                    "\u026b": "l",
                    "\u2c61": "l",
                    "\ua749": "l",
                    "\ua781": "l",
                    "\ua747": "l",
                    "\u01c9": "lj",
                    "\u24dc": "m",
                    "\uff4d": "m",
                    "\u1e3f": "m",
                    "\u1e41": "m",
                    "\u1e43": "m",
                    "\u0271": "m",
                    "\u026f": "m",
                    "\u24dd": "n",
                    "\uff4e": "n",
                    "\u01f9": "n",
                    "\u0144": "n",
                    "\xf1": "n",
                    "\u1e45": "n",
                    "\u0148": "n",
                    "\u1e47": "n",
                    "\u0146": "n",
                    "\u1e4b": "n",
                    "\u1e49": "n",
                    "\u019e": "n",
                    "\u0272": "n",
                    "\u0149": "n",
                    "\ua791": "n",
                    "\ua7a5": "n",
                    "\u01cc": "nj",
                    "\u24de": "o",
                    "\uff4f": "o",
                    "\xf2": "o",
                    "\xf3": "o",
                    "\xf4": "o",
                    "\u1ed3": "o",
                    "\u1ed1": "o",
                    "\u1ed7": "o",
                    "\u1ed5": "o",
                    "\xf5": "o",
                    "\u1e4d": "o",
                    "\u022d": "o",
                    "\u1e4f": "o",
                    "\u014d": "o",
                    "\u1e51": "o",
                    "\u1e53": "o",
                    "\u014f": "o",
                    "\u022f": "o",
                    "\u0231": "o",
                    "\xf6": "o",
                    "\u022b": "o",
                    "\u1ecf": "o",
                    "\u0151": "o",
                    "\u01d2": "o",
                    "\u020d": "o",
                    "\u020f": "o",
                    "\u01a1": "o",
                    "\u1edd": "o",
                    "\u1edb": "o",
                    "\u1ee1": "o",
                    "\u1edf": "o",
                    "\u1ee3": "o",
                    "\u1ecd": "o",
                    "\u1ed9": "o",
                    "\u01eb": "o",
                    "\u01ed": "o",
                    "\xf8": "o",
                    "\u01ff": "o",
                    "\u0254": "o",
                    "\ua74b": "o",
                    "\ua74d": "o",
                    "\u0275": "o",
                    "\u01a3": "oi",
                    "\u0223": "ou",
                    "\ua74f": "oo",
                    "\u24df": "p",
                    "\uff50": "p",
                    "\u1e55": "p",
                    "\u1e57": "p",
                    "\u01a5": "p",
                    "\u1d7d": "p",
                    "\ua751": "p",
                    "\ua753": "p",
                    "\ua755": "p",
                    "\u24e0": "q",
                    "\uff51": "q",
                    "\u024b": "q",
                    "\ua757": "q",
                    "\ua759": "q",
                    "\u24e1": "r",
                    "\uff52": "r",
                    "\u0155": "r",
                    "\u1e59": "r",
                    "\u0159": "r",
                    "\u0211": "r",
                    "\u0213": "r",
                    "\u1e5b": "r",
                    "\u1e5d": "r",
                    "\u0157": "r",
                    "\u1e5f": "r",
                    "\u024d": "r",
                    "\u027d": "r",
                    "\ua75b": "r",
                    "\ua7a7": "r",
                    "\ua783": "r",
                    "\u24e2": "s",
                    "\uff53": "s",
                    "\xdf": "s",
                    "\u015b": "s",
                    "\u1e65": "s",
                    "\u015d": "s",
                    "\u1e61": "s",
                    "\u0161": "s",
                    "\u1e67": "s",
                    "\u1e63": "s",
                    "\u1e69": "s",
                    "\u0219": "s",
                    "\u015f": "s",
                    "\u023f": "s",
                    "\ua7a9": "s",
                    "\ua785": "s",
                    "\u1e9b": "s",
                    "\u24e3": "t",
                    "\uff54": "t",
                    "\u1e6b": "t",
                    "\u1e97": "t",
                    "\u0165": "t",
                    "\u1e6d": "t",
                    "\u021b": "t",
                    "\u0163": "t",
                    "\u1e71": "t",
                    "\u1e6f": "t",
                    "\u0167": "t",
                    "\u01ad": "t",
                    "\u0288": "t",
                    "\u2c66": "t",
                    "\ua787": "t",
                    "\ua729": "tz",
                    "\u24e4": "u",
                    "\uff55": "u",
                    "\xf9": "u",
                    "\xfa": "u",
                    "\xfb": "u",
                    "\u0169": "u",
                    "\u1e79": "u",
                    "\u016b": "u",
                    "\u1e7b": "u",
                    "\u016d": "u",
                    "\xfc": "u",
                    "\u01dc": "u",
                    "\u01d8": "u",
                    "\u01d6": "u",
                    "\u01da": "u",
                    "\u1ee7": "u",
                    "\u016f": "u",
                    "\u0171": "u",
                    "\u01d4": "u",
                    "\u0215": "u",
                    "\u0217": "u",
                    "\u01b0": "u",
                    "\u1eeb": "u",
                    "\u1ee9": "u",
                    "\u1eef": "u",
                    "\u1eed": "u",
                    "\u1ef1": "u",
                    "\u1ee5": "u",
                    "\u1e73": "u",
                    "\u0173": "u",
                    "\u1e77": "u",
                    "\u1e75": "u",
                    "\u0289": "u",
                    "\u24e5": "v",
                    "\uff56": "v",
                    "\u1e7d": "v",
                    "\u1e7f": "v",
                    "\u028b": "v",
                    "\ua75f": "v",
                    "\u028c": "v",
                    "\ua761": "vy",
                    "\u24e6": "w",
                    "\uff57": "w",
                    "\u1e81": "w",
                    "\u1e83": "w",
                    "\u0175": "w",
                    "\u1e87": "w",
                    "\u1e85": "w",
                    "\u1e98": "w",
                    "\u1e89": "w",
                    "\u2c73": "w",
                    "\u24e7": "x",
                    "\uff58": "x",
                    "\u1e8b": "x",
                    "\u1e8d": "x",
                    "\u24e8": "y",
                    "\uff59": "y",
                    "\u1ef3": "y",
                    "\xfd": "y",
                    "\u0177": "y",
                    "\u1ef9": "y",
                    "\u0233": "y",
                    "\u1e8f": "y",
                    "\xff": "y",
                    "\u1ef7": "y",
                    "\u1e99": "y",
                    "\u1ef5": "y",
                    "\u01b4": "y",
                    "\u024f": "y",
                    "\u1eff": "y",
                    "\u24e9": "z",
                    "\uff5a": "z",
                    "\u017a": "z",
                    "\u1e91": "z",
                    "\u017c": "z",
                    "\u017e": "z",
                    "\u1e93": "z",
                    "\u1e95": "z",
                    "\u01b6": "z",
                    "\u0225": "z",
                    "\u0240": "z",
                    "\u2c6c": "z",
                    "\ua763": "z",
                    "\u0386": "\u0391",
                    "\u0388": "\u0395",
                    "\u0389": "\u0397",
                    "\u038a": "\u0399",
                    "\u03aa": "\u0399",
                    "\u038c": "\u039f",
                    "\u038e": "\u03a5",
                    "\u03ab": "\u03a5",
                    "\u038f": "\u03a9",
                    "\u03ac": "\u03b1",
                    "\u03ad": "\u03b5",
                    "\u03ae": "\u03b7",
                    "\u03af": "\u03b9",
                    "\u03ca": "\u03b9",
                    "\u0390": "\u03b9",
                    "\u03cc": "\u03bf",
                    "\u03cd": "\u03c5",
                    "\u03cb": "\u03c5",
                    "\u03b0": "\u03c5",
                    "\u03c9": "\u03c9",
                    "\u03c2": "\u03c3"
                };
            F = e(document), j = function () {
                var e = 1;
                return function () {
                    return e++
                }
            }(), N = M(Object, {
                "bind": function (e) {
                    var t = this;
                    return function () {
                        e.apply(t, arguments)
                    }
                },
                "init": function (i) {
                    var n, s, a = ".select2-results";
                    this.opts = i = this.prepareOpts(i), this.id = i.id, i.element.data("select2") !== t && null !== i.element.data("select2") && i.element.data("select2").destroy(), this.container = this.createContainer(), this.liveRegion = e(".select2-hidden-accessible"), 0 == this.liveRegion.length && (this.liveRegion = e("<span>", {
                        "role": "status",
                        "aria-live": "polite"
                    }).addClass("select2-hidden-accessible").appendTo(document.body)), this.containerId = "s2id_" + (i.element.attr("id") || "autogen" + j()), this.containerEventName = this.containerId.replace(/([.])/g, "_").replace(/([;&,\-\.\+\*\~':"\!\^#$%@\[\]\(\)=>\|])/g, "\\$1"), this.container.attr("id", this.containerId), this.container.attr("title", i.element.attr("title")), this.body = e(document.body), y(this.container, this.opts.element, this.opts.adaptContainerCssClass), this.container.attr("style", i.element.attr("style")), this.container.css(D(i.containerCss, this.opts.element)), this.container.addClass(D(i.containerCssClass, this.opts.element)), this.elementTabIndex = this.opts.element.attr("tabindex"), this.opts.element.data("select2", this).attr("tabindex", "-1").before(this.container).on("click.select2", m), this.container.data("select2", this), this.dropdown = this.container.find(".select2-drop"), y(this.dropdown, this.opts.element, this.opts.adaptDropdownCssClass), this.dropdown.addClass(D(i.dropdownCssClass, this.opts.element)), this.dropdown.data("select2", this), this.dropdown.on("click", m), this.results = n = this.container.find(a), this.search = s = this.container.find("input.select2-input"), this.queryCount = 0, this.resultsPage = 0, this.context = null, this.initContainer(), this.container.on("click", m), u(this.results), this.dropdown.on("mousemove-filtered", a, this.bind(this.highlightUnderEvent)), this.dropdown.on("touchstart touchmove touchend", a, this.bind(function (e) {
                        this._touchEvent = !0, this.highlightUnderEvent(e)
                    })), this.dropdown.on("touchmove", a, this.bind(this.touchMoved)), this.dropdown.on("touchstart touchend", a, this.bind(this.clearTouchMoved)), this.dropdown.on("click", this.bind(function () {
                        this._touchEvent && (this._touchEvent = !1, this.selectHighlighted())
                    })), d(80, this.results), this.dropdown.on("scroll-debounced", a, this.bind(this.loadMoreIfNeeded)), e(this.container).on("change", ".select2-input", function (e) {
                        e.stopPropagation()
                    }), e(this.dropdown).on("change", ".select2-input", function (e) {
                        e.stopPropagation()
                    }), e.fn.mousewheel && n.mousewheel(function (e, t, i, s) {
                        var r = n.scrollTop();
                        s > 0 && 0 >= r - s ? (n.scrollTop(0), m(e)) : 0 > s && n.get(0).scrollHeight - n.scrollTop() + s <= n.height() && (n.scrollTop(n.get(0).scrollHeight - n.height()), m(e))
                    }), c(s), s.on("keyup-change input paste", this.bind(this.updateResults)), s.on("focus", function () {
                        s.addClass("select2-focused")
                    }), s.on("blur", function () {
                        s.removeClass("select2-focused")
                    }), this.dropdown.on("mouseup", a, this.bind(function (t) {
                        e(t.target).closest(".select2-result-selectable").length > 0 && (this.highlightUnderEvent(t), this.selectHighlighted(t))
                    })), this.dropdown.on("click mouseup mousedown touchstart touchend focusin", function (e) {
                        e.stopPropagation()
                    }), this.nextSearchTerm = t, e.isFunction(this.opts.initSelection) && (this.initSelection(), this.monitorSource()), null !== i.maximumInputLength && this.search.attr("maxlength", i.maximumInputLength);
                    var o = i.element.prop("disabled");
                    o === t && (o = !1), this.enable(!o);
                    var l = i.element.prop("readonly");
                    l === t && (l = !1), this.readonly(l), L = L || r(), this.autofocus = i.element.prop("autofocus"), i.element.prop("autofocus", !1), this.autofocus && this.focus(), this.search.attr("placeholder", i.searchInputPlaceholder)
                },
                "destroy": function () {
                    var e = this.opts.element,
                        i = e.data("select2"),
                        n = this;
                    this.close(), e.length && e[0].detachEvent && n._sync && e.each(function () {
                        n._sync && this.detachEvent("onpropertychange", n._sync)
                    }), this.propertyObserver && (this.propertyObserver.disconnect(), this.propertyObserver = null), this._sync = null, i !== t && (i.container.remove(), i.liveRegion.remove(), i.dropdown.remove(), e.show().removeData("select2").off(".select2").prop("autofocus", this.autofocus || !1), this.elementTabIndex ? e.attr({
                        "tabindex": this.elementTabIndex
                    }) : e.removeAttr("tabindex"), e.show()), E.call(this, "container", "liveRegion", "dropdown", "results", "search")
                },
                "optionToData": function (e) {
                    return e.is("option") ? {
                        "id": e.prop("value"),
                        "text": e.text(),
                        "element": e.get(),
                        "css": e.attr("class"),
                        "disabled": e.prop("disabled"),
                        "locked": a(e.attr("locked"), "locked") || a(e.data("locked"), !0)
                    } : e.is("optgroup") ? {
                        "text": e.attr("label"),
                        "children": [],
                        "element": e.get(),
                        "css": e.attr("class")
                    } : void 0
                },
                "prepareOpts": function (i) {
                    var n, s, r, l, c = this;
                    if (n = i.element, "select" === n.get(0).tagName.toLowerCase() && (this.select = s = i.element), s && e.each(["id", "multiple", "ajax", "query", "createSearchChoice", "initSelection", "data", "tags"], function () {
                            if (this in i) throw new Error("Option '" + this + "' is not allowed for Select2 when attached to a <select> element.")
                        }), i = e.extend({}, {
                            "populateResults": function (n, s, r) {
                                var a, o = this.opts.id,
                                    l = this.liveRegion;
                                (a = function (n, s, u) {
                                    var h, d, p, f, m, g, v, y, b, w;
                                    n = i.sortResults(n, s, r);
                                    var x = [];
                                    for (h = 0, d = n.length; d > h; h += 1) p = n[h], m = p.disabled === !0, f = !m && o(p) !== t, g = p.children && p.children.length > 0, v = e("<li></li>"), v.addClass("select2-results-dept-" + u), v.addClass("select2-result"), v.addClass(f ? "select2-result-selectable" : "select2-result-unselectable"), m && v.addClass("select2-disabled"), g && v.addClass("select2-result-with-children"), v.addClass(c.opts.formatResultCssClass(p)), v.attr("role", "presentation"), y = e(document.createElement("div")), y.addClass("select2-result-label"), y.attr("id", "select2-result-label-" + j()), y.attr("role", "option"), w = i.formatResult(p, y, r, c.opts.escapeMarkup), w !== t && (y.html(w), v.append(y)), g && (b = e("<ul></ul>"), b.addClass("select2-result-sub"), a(p.children, b, u + 1), v.append(b)), v.data("select2-data", p), x.push(v[0]);
                                    s.append(x), l.text(i.formatMatches(n.length))
                                })(s, n, 0)
                            }
                        }, e.fn.select2.defaults, i), "function" != typeof i.id && (r = i.id, i.id = function (e) {
                            return e[r]
                        }), e.isArray(i.element.data("select2Tags"))) {
                        if ("tags" in i) throw "tags specified as both an attribute 'data-select2-tags' and in options of Select2 " + i.element.attr("id");
                        i.tags = i.element.data("select2Tags")
                    }
                    if (s ? (i.query = this.bind(function (e) {
                            var i, s, r, a = {
                                    "results": [],
                                    "more": !1
                                },
                                o = e.term;
                            r = function (t, i) {
                                var n;
                                t.is("option") ? e.matcher(o, t.text(), t) && i.push(c.optionToData(t)) : t.is("optgroup") && (n = c.optionToData(t), t.children().each2(function (e, t) {
                                    r(t, n.children)
                                }), n.children.length > 0 && i.push(n))
                            }, i = n.children(), this.getPlaceholder() !== t && i.length > 0 && (s = this.getPlaceholderOption(), s && (i = i.not(s))), i.each2(function (e, t) {
                                r(t, a.results)
                            }), e.callback(a)
                        }), i.id = function (e) {
                            return e.id
                        }) : "query" in i || ("ajax" in i ? (l = i.element.data("ajax-url"), l && l.length > 0 && (i.ajax.url = l), i.query = x.call(i.element, i.ajax)) : "data" in i ? i.query = k(i.data) : "tags" in i && (i.query = _(i.tags), i.createSearchChoice === t && (i.createSearchChoice = function (t) {
                            return {
                                "id": e.trim(t),
                                "text": e.trim(t)
                            }
                        }), i.initSelection === t && (i.initSelection = function (t, n) {
                            var s = [];
                            e(o(t.val(), i.separator, i.transformVal)).each(function () {
                                var t = {
                                        "id": this,
                                        "text": this
                                    },
                                    n = i.tags;
                                e.isFunction(n) && (n = n()), e(n).each(function () {
                                    return a(this.id, t.id) ? (t = this, !1) : void 0
                                }), s.push(t)
                            }), n(s)
                        }))), "function" != typeof i.query) throw "query function not defined for Select2 " + i.element.attr("id");
                    if ("top" === i.createSearchChoicePosition) i.createSearchChoicePosition = function (e, t) {
                        e.unshift(t)
                    };
                    else if ("bottom" === i.createSearchChoicePosition) i.createSearchChoicePosition = function (e, t) {
                        e.push(t)
                    };
                    else if ("function" != typeof i.createSearchChoicePosition) throw "invalid createSearchChoicePosition option must be 'top', 'bottom' or a custom function";
                    return i
                },
                "monitorSource": function () {
                    var i, n = this.opts.element,
                        s = this;
                    n.on("change.select2", this.bind(function () {
                        this.opts.element.data("select2-change-triggered") !== !0 && this.initSelection()
                    })), this._sync = this.bind(function () {
                        var e = n.prop("disabled");
                        e === t && (e = !1), this.enable(!e);
                        var i = n.prop("readonly");
                        i === t && (i = !1), this.readonly(i), this.container && (y(this.container, this.opts.element, this.opts.adaptContainerCssClass), this.container.addClass(D(this.opts.containerCssClass, this.opts.element))), this.dropdown && (y(this.dropdown, this.opts.element, this.opts.adaptDropdownCssClass), this.dropdown.addClass(D(this.opts.dropdownCssClass, this.opts.element)))
                    }), n.length && n[0].attachEvent && n.each(function () {
                        this.attachEvent("onpropertychange", s._sync)
                    }), i = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver, i !== t && (this.propertyObserver && (delete this.propertyObserver, this.propertyObserver = null), this.propertyObserver = new i(function (t) {
                        e.each(t, s._sync)
                    }), this.propertyObserver.observe(n.get(0), {
                        "attributes": !0,
                        "subtree": !1
                    }))
                },
                "triggerSelect": function (t) {
                    var i = e.Event("select2-selecting", {
                        "val": this.id(t),
                        "object": t,
                        "choice": t
                    });
                    return this.opts.element.trigger(i), !i.isDefaultPrevented()
                },
                "triggerChange": function (t) {
                    t = t || {}, t = e.extend({}, t, {
                        "type": "change",
                        "val": this.val()
                    }), this.opts.element.data("select2-change-triggered", !0), this.opts.element.trigger(t), this.opts.element.data("select2-change-triggered", !1), this.opts.element.click(), this.opts.blurOnChange && this.opts.element.blur()
                },
                "isInterfaceEnabled": function () {
                    return this.enabledInterface === !0
                },
                "enableInterface": function () {
                    var e = this._enabled && !this._readonly,
                        t = !e;
                    return e === this.enabledInterface ? !1 : (this.container.toggleClass("select2-container-disabled", t), this.close(), this.enabledInterface = e, !0)
                },
                "enable": function (e) {
                    e === t && (e = !0), this._enabled !== e && (this._enabled = e, this.opts.element.prop("disabled", !e), this.enableInterface())
                },
                "disable": function () {
                    this.enable(!1)
                },
                "readonly": function (e) {
                    e === t && (e = !1), this._readonly !== e && (this._readonly = e, this.opts.element.prop("readonly", e), this.enableInterface())
                },
                "opened": function () {
                    return this.container ? this.container.hasClass("select2-dropdown-open") : !1
                },
                "positionDropdown": function () {
                    var t, i, n, s, r, a = this.dropdown,
                        o = this.container,
                        l = o.offset(),
                        c = o.outerHeight(!1),
                        u = o.outerWidth(!1),
                        h = a.outerHeight(!1),
                        d = e(window),
                        p = d.width(),
                        f = d.height(),
                        m = d.scrollLeft() + p,
                        g = d.scrollTop() + f,
                        v = l.top + c,
                        y = l.left,
                        b = g >= v + h,
                        w = l.top - h >= d.scrollTop(),
                        x = a.outerWidth(!1),
                        k = function () {
                            return m >= y + x
                        },
                        _ = function () {
                            return l.left + m + o.outerWidth(!1) > x
                        },
                        C = a.hasClass("select2-drop-above");
                    C ? (i = !0, !w && b && (n = !0, i = !1)) : (i = !1, !b && w && (n = !0, i = !0)), n && (a.hide(), l = this.container.offset(), c = this.container.outerHeight(!1), u = this.container.outerWidth(!1), h = a.outerHeight(!1), m = d.scrollLeft() + p, g = d.scrollTop() + f, v = l.top + c, y = l.left, x = a.outerWidth(!1), a.show(), this.focusSearch()), this.opts.dropdownAutoWidth ? (r = e(".select2-results", a)[0], a.addClass("select2-drop-auto-width"), a.css("width", ""), x = a.outerWidth(!1) + (r.scrollHeight === r.clientHeight ? 0 : L.width), x > u ? u = x : x = u, h = a.outerHeight(!1)) : this.container.removeClass("select2-drop-auto-width"), "static" !== this.body.css("position") && (t = this.body.offset(), v -= t.top, y -= t.left), !k() && _() && (y = l.left + this.container.outerWidth(!1) - x), s = {
                        "left": y,
                        "width": u
                    }, i ? (s.top = l.top - h, s.bottom = "auto", this.container.addClass("select2-drop-above"), a.addClass("select2-drop-above")) : (s.top = v, s.bottom = "auto", this.container.removeClass("select2-drop-above"), a.removeClass("select2-drop-above")), s = e.extend(s, D(this.opts.dropdownCss, this.opts.element)), a.css(s)
                },
                "shouldOpen": function () {
                    var t;
                    return this.opened() ? !1 : this._enabled === !1 || this._readonly === !0 ? !1 : (t = e.Event("select2-opening"), this.opts.element.trigger(t), !t.isDefaultPrevented())
                },
                "clearDropdownAlignmentPreference": function () {
                    this.container.removeClass("select2-drop-above"), this.dropdown.removeClass("select2-drop-above")
                },
                "open": function () {
                    return this.shouldOpen() ? (this.opening(), F.on("mousemove.select2Event", function (e) {
                        P.x = e.pageX, P.y = e.pageY
                    }), !0) : !1
                },
                "opening": function () {
                    var t, n = this.containerEventName,
                        s = "scroll." + n,
                        r = "resize." + n,
                        a = "orientationchange." + n;
                    this.container.addClass("select2-dropdown-open").addClass("select2-container-active"), this.clearDropdownAlignmentPreference(), this.dropdown[0] !== this.body.children().last()[0] && this.dropdown.detach().appendTo(this.body), t = e("#select2-drop-mask"), 0 === t.length && (t = e(document.createElement("div")), t.attr("id", "select2-drop-mask").attr("class", "select2-drop-mask"), t.hide(), t.appendTo(this.body), t.on("mousedown touchstart click", function (n) {
                        i(t);
                        var s, r = e("#select2-drop");
                        r.length > 0 && (s = r.data("select2"), s.opts.selectOnBlur && s.selectHighlighted({
                            "noFocus": !0
                        }), s.close(), n.preventDefault(), n.stopPropagation())
                    })), this.dropdown.prev()[0] !== t[0] && this.dropdown.before(t), e("#select2-drop").removeAttr("id"), this.dropdown.attr("id", "select2-drop"), t.show(), this.positionDropdown(), this.dropdown.show(), this.positionDropdown(), this.dropdown.addClass("select2-drop-active");
                    var o = this;
                    this.container.parents().add(window).each(function () {
                        e(this).on(r + " " + s + " " + a, function () {
                            o.opened() && o.positionDropdown()
                        })
                    })
                },
                "close": function () {
                    if (this.opened()) {
                        var t = this.containerEventName,
                            i = "scroll." + t,
                            n = "resize." + t,
                            s = "orientationchange." + t;
                        this.container.parents().add(window).each(function () {
                            e(this).off(i).off(n).off(s)
                        }), this.clearDropdownAlignmentPreference(), e("#select2-drop-mask").hide(), this.dropdown.removeAttr("id"), this.dropdown.hide(), this.container.removeClass("select2-dropdown-open").removeClass("select2-container-active"), this.results.empty(), F.off("mousemove.select2Event"), this.clearSearch(), this.search.removeClass("select2-active"), this.opts.element.trigger(e.Event("select2-close"))
                    }
                },
                "externalSearch": function (e) {
                    this.open(), this.search.val(e), this.updateResults(!1)
                },
                "clearSearch": function () {},
                "getMaximumSelectionSize": function () {
                    return D(this.opts.maximumSelectionSize, this.opts.element)
                },
                "ensureHighlightVisible": function () {
                    var t, i, n, s, r, a, o, l, c = this.results;
                    if (i = this.highlight(), !(0 > i)) {
                        if (0 == i) return void c.scrollTop(0);
                        t = this.findHighlightableChoices().find(".select2-result-label"), n = e(t[i]), l = (n.offset() || {}).top || 0, s = l + n.outerHeight(!0), i === t.length - 1 && (o = c.find("li.select2-more-results"), o.length > 0 && (s = o.offset().top + o.outerHeight(!0))), r = c.offset().top + c.outerHeight(!1), s > r && c.scrollTop(c.scrollTop() + (s - r)), a = l - c.offset().top, 0 > a && "none" != n.css("display") && c.scrollTop(c.scrollTop() + a)
                    }
                },
                "findHighlightableChoices": function () {
                    return this.results.find(".select2-result-selectable:not(.select2-disabled):not(.select2-selected)")
                },
                "moveHighlight": function (t) {
                    for (var i = this.findHighlightableChoices(), n = this.highlight(); n > -1 && n < i.length;) {
                        n += t;
                        var s = e(i[n]);
                        if (s.hasClass("select2-result-selectable") && !s.hasClass("select2-disabled") && !s.hasClass("select2-selected")) {
                            this.highlight(n);
                            break
                        }
                    }
                },
                "highlight": function (t) {
                    var i, n, r = this.findHighlightableChoices();
                    return 0 === arguments.length ? s(r.filter(".select2-highlighted")[0], r.get()) : (t >= r.length && (t = r.length - 1), 0 > t && (t = 0), this.removeHighlight(), i = e(r[t]), i.addClass("select2-highlighted"), this.search.attr("aria-activedescendant", i.find(".select2-result-label").attr("id")), this.ensureHighlightVisible(), this.liveRegion.text(i.text()), n = i.data("select2-data"), void(n && this.opts.element.trigger({
                        "type": "select2-highlight",
                        "val": this.id(n),
                        "choice": n
                    })))
                },
                "removeHighlight": function () {
                    this.results.find(".select2-highlighted").removeClass("select2-highlighted")
                },
                "touchMoved": function () {
                    this._touchMoved = !0
                },
                "clearTouchMoved": function () {
                    this._touchMoved = !1
                },
                "countSelectableResults": function () {
                    return this.findHighlightableChoices().length
                },
                "highlightUnderEvent": function (t) {
                    var i = e(t.target).closest(".select2-result-selectable");
                    if (i.length > 0 && !i.is(".select2-highlighted")) {
                        var n = this.findHighlightableChoices();
                        this.highlight(n.index(i))
                    } else 0 == i.length && this.removeHighlight()
                },
                "loadMoreIfNeeded": function () {
                    var e, t = this.results,
                        i = t.find("li.select2-more-results"),
                        n = this.resultsPage + 1,
                        s = this,
                        r = this.search.val(),
                        a = this.context;
                    0 !== i.length && (e = i.offset().top - t.offset().top - t.height(), e <= this.opts.loadMorePadding && (i.addClass("select2-active"), this.opts.query({
                        "element": this.opts.element,
                        "term": r,
                        "page": n,
                        "context": a,
                        "matcher": this.opts.matcher,
                        "callback": this.bind(function (e) {
                            s.opened() && (s.opts.populateResults.call(this, t, e.results, {
                                "term": r,
                                "page": n,
                                "context": a
                            }), s.postprocessResults(e, !1, !1), e.more === !0 ? (i.detach().appendTo(t).html(s.opts.escapeMarkup(D(s.opts.formatLoadMore, s.opts.element, n + 1))), window.setTimeout(function () {
                                s.loadMoreIfNeeded()
                            }, 10)) : i.remove(), s.positionDropdown(), s.resultsPage = n, s.context = e.context, this.opts.element.trigger({
                                "type": "select2-loaded",
                                "items": e
                            }))
                        })
                    })))
                },
                "tokenize": function () {},
                "updateResults": function (i) {
                    function n() {
                        c.removeClass("select2-active"), d.positionDropdown(), d.liveRegion.text(u.find(".select2-no-results,.select2-selection-limit,.select2-searching").length ? u.text() : d.opts.formatMatches(u.find('.select2-result-selectable:not(".select2-selected")').length))
                    }

                    function s(e) {
                        u.html(e), n()
                    }
                    var r, o, l, c = this.search,
                        u = this.results,
                        h = this.opts,
                        d = this,
                        p = c.val(),
                        f = e.data(this.container, "select2-last-term");
                    if ((i === !0 || !f || !a(p, f)) && (e.data(this.container, "select2-last-term", p), i === !0 || this.showSearchInput !== !1 && this.opened())) {
                        l = ++this.queryCount;
                        var m = this.getMaximumSelectionSize();
                        if (m >= 1 && (r = this.data(), e.isArray(r) && r.length >= m && C(h.formatSelectionTooBig, "formatSelectionTooBig"))) return void s("<li class='select2-selection-limit'>" + D(h.formatSelectionTooBig, h.element, m) + "</li>");
                        if (c.val().length < h.minimumInputLength) return s(C(h.formatInputTooShort, "formatInputTooShort") ? "<li class='select2-no-results'>" + D(h.formatInputTooShort, h.element, c.val(), h.minimumInputLength) + "</li>" : ""), void(i && this.showSearch && this.showSearch(!0));
                        if (h.maximumInputLength && c.val().length > h.maximumInputLength) return void s(C(h.formatInputTooLong, "formatInputTooLong") ? "<li class='select2-no-results'>" + D(h.formatInputTooLong, h.element, c.val(), h.maximumInputLength) + "</li>" : "");
                        h.formatSearching && 0 === this.findHighlightableChoices().length && s("<li class='select2-searching'>" + D(h.formatSearching, h.element) + "</li>"), c.addClass("select2-active"), this.removeHighlight(), o = this.tokenize(), o != t && null != o && c.val(o), this.resultsPage = 1, h.query({
                            "element": h.element,
                            "term": c.val(),
                            "page": this.resultsPage,
                            "context": null,
                            "matcher": h.matcher,
                            "callback": this.bind(function (r) {
                                var o;
                                if (l == this.queryCount) {
                                    if (!this.opened()) return void this.search.removeClass("select2-active");
                                    if (r.hasError !== t && C(h.formatAjaxError, "formatAjaxError")) return void s("<li class='select2-ajax-error'>" + D(h.formatAjaxError, h.element, r.jqXHR, r.textStatus, r.errorThrown) + "</li>");
                                    if (this.context = r.context === t ? null : r.context, this.opts.createSearchChoice && "" !== c.val() && (o = this.opts.createSearchChoice.call(d, c.val(), r.results), o !== t && null !== o && d.id(o) !== t && null !== d.id(o) && 0 === e(r.results).filter(function () {
                                            return a(d.id(this), d.id(o))
                                        }).length && this.opts.createSearchChoicePosition(r.results, o)), 0 === r.results.length && C(h.formatNoMatches, "formatNoMatches")) return void s("<li class='select2-no-results'>" + D(h.formatNoMatches, h.element, c.val()) + "</li>");
                                    u.empty(), d.opts.populateResults.call(this, u, r.results, {
                                        "term": c.val(),
                                        "page": this.resultsPage,
                                        "context": null
                                    }), r.more === !0 && C(h.formatLoadMore, "formatLoadMore") && (u.append("<li class='select2-more-results'>" + h.escapeMarkup(D(h.formatLoadMore, h.element, this.resultsPage)) + "</li>"), window.setTimeout(function () {
                                        d.loadMoreIfNeeded()
                                    }, 10)), this.postprocessResults(r, i), n(), this.opts.element.trigger({
                                        "type": "select2-loaded",
                                        "items": r
                                    })
                                }
                            })
                        })
                    }
                },
                "cancel": function () {
                    this.close()
                },
                "blur": function () {
                    this.opts.selectOnBlur && this.selectHighlighted({
                        "noFocus": !0
                    }), this.close(), this.container.removeClass("select2-container-active"), this.search[0] === document.activeElement && this.search.blur(), this.clearSearch(), this.selection.find(".select2-search-choice-focus").removeClass("select2-search-choice-focus")
                },
                "focusSearch": function () {
                    p(this.search)
                },
                "selectHighlighted": function (e) {
                    if (this._touchMoved) return void this.clearTouchMoved();
                    var t = this.highlight(),
                        i = this.results.find(".select2-highlighted"),
                        n = i.closest(".select2-result").data("select2-data");
                    n ? (this.highlight(t), this.onSelect(n, e)) : e && e.noFocus && this.close()
                },
                "getPlaceholder": function () {
                    var e;
                    return this.opts.element.attr("placeholder") || this.opts.element.attr("data-placeholder") || this.opts.element.data("placeholder") || this.opts.placeholder || ((e = this.getPlaceholderOption()) !== t ? e.text() : t)
                },
                "getPlaceholderOption": function () {
                    if (this.select) {
                        var i = this.select.children("option").first();
                        if (this.opts.placeholderOption !== t) return "first" === this.opts.placeholderOption && i || "function" == typeof this.opts.placeholderOption && this.opts.placeholderOption(this.select);
                        if ("" === e.trim(i.text()) && "" === i.val()) return i
                    }
                },
                "initContainerWidth": function () {
                    function i() {
                        var i, n, s, r, a, o;
                        if ("off" === this.opts.width) return null;
                        if ("element" === this.opts.width) return 0 === this.opts.element.outerWidth(!1) ? "auto" : this.opts.element.outerWidth(!1) + "px";
                        if ("copy" === this.opts.width || "resolve" === this.opts.width) {
                            if (i = this.opts.element.attr("style"), i !== t)
                                for (n = i.split(";"), r = 0, a = n.length; a > r; r += 1)
                                    if (o = n[r].replace(/\s/g, ""), s = o.match(/^width:(([-+]?([0-9]*\.)?[0-9]+)(px|em|ex|%|in|cm|mm|pt|pc))/i), null !== s && s.length >= 1) return s[1];
                            return "resolve" === this.opts.width ? (i = this.opts.element.css("width"), i.indexOf("%") > 0 ? i : 0 === this.opts.element.outerWidth(!1) ? "auto" : this.opts.element.outerWidth(!1) + "px") : null
                        }
                        return e.isFunction(this.opts.width) ? this.opts.width() : this.opts.width
                    }
                    var n = i.call(this);
                    null !== n && this.container.css("width", n)
                }
            }), A = M(N, {
                "createContainer": function () {
                    var t = e(document.createElement("div")).attr({
                        "class": "select2-container"
                    }).html(["<a href='javascript:void(0)' class='select2-choice' tabindex='-1'>", "   <span class='select2-chosen'>&#160;</span><abbr class='select2-search-choice-close'></abbr>", "   <span class='select2-arrow' role='presentation'><b role='presentation'></b></span>", "</a>", "<label for='' class='select2-offscreen'></label>", "<input class='select2-focusser select2-offscreen' type='text' aria-haspopup='true' role='button' />", "<div class='select2-drop select2-display-none'>", "   <div class='select2-search'>", "       <label for='' class='select2-offscreen'></label>", "       <input type='text' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' class='select2-input' role='combobox' aria-expanded='true'", "       aria-autocomplete='list' />", "   </div>", "   <ul class='select2-results' role='listbox'>", "   </ul>", "</div>"].join(""));
                    return t
                },
                "enableInterface": function () {
                    this.parent.enableInterface.apply(this, arguments) && this.focusser.prop("disabled", !this.isInterfaceEnabled())
                },
                "opening": function () {
                    var i, n, s;
                    this.opts.minimumResultsForSearch >= 0 && this.showSearch(!0), this.parent.opening.apply(this, arguments), this.showSearchInput !== !1 && this.search.val(this.focusser.val()), this.opts.shouldFocusInput(this) && (this.search.focus(), i = this.search.get(0), i.createTextRange ? (n = i.createTextRange(), n.collapse(!1), n.select()) : i.setSelectionRange && (s = this.search.val().length, i.setSelectionRange(s, s))), "" === this.search.val() && this.nextSearchTerm != t && (this.search.val(this.nextSearchTerm), this.search.select()), this.focusser.prop("disabled", !0).val(""), this.updateResults(!0), this.opts.element.trigger(e.Event("select2-open"))
                },
                "close": function () {
                    this.opened() && (this.parent.close.apply(this, arguments), this.focusser.prop("disabled", !1), this.opts.shouldFocusInput(this) && this.focusser.focus())
                },
                "focus": function () {
                    this.opened() ? this.close() : (this.focusser.prop("disabled", !1), this.opts.shouldFocusInput(this) && this.focusser.focus())
                },
                "isFocused": function () {
                    return this.container.hasClass("select2-container-active")
                },
                "cancel": function () {
                    this.parent.cancel.apply(this, arguments), this.focusser.prop("disabled", !1), this.opts.shouldFocusInput(this) && this.focusser.focus()
                },
                "destroy": function () {
                    e("label[for='" + this.focusser.attr("id") + "']").attr("for", this.opts.element.attr("id")), this.parent.destroy.apply(this, arguments), E.call(this, "selection", "focusser")
                },
                "initContainer": function () {
                    var t, n, s = this.container,
                        r = this.dropdown,
                        a = j();
                    this.showSearch(this.opts.minimumResultsForSearch < 0 ? !1 : !0), this.selection = t = s.find(".select2-choice"), this.focusser = s.find(".select2-focusser"), t.find(".select2-chosen").attr("id", "select2-chosen-" + a), this.focusser.attr("aria-labelledby", "select2-chosen-" + a), this.results.attr("id", "select2-results-" + a), this.search.attr("aria-owns", "select2-results-" + a), this.focusser.attr("id", "s2id_autogen" + a), n = e("label[for='" + this.opts.element.attr("id") + "']"), this.opts.element.focus(this.bind(function () {
                        this.focus()
                    })), this.focusser.prev().text(n.text()).attr("for", this.focusser.attr("id"));
                    var o = this.opts.element.attr("title");
                    this.opts.element.attr("title", o || n.text()), this.focusser.attr("tabindex", this.elementTabIndex), this.search.attr("id", this.focusser.attr("id") + "_search"), this.search.prev().text(e("label[for='" + this.focusser.attr("id") + "']").text()).attr("for", this.search.attr("id")), this.search.on("keydown", this.bind(function (e) {
                        if (this.isInterfaceEnabled() && 229 != e.keyCode) {
                            if (e.which === H.PAGE_UP || e.which === H.PAGE_DOWN) return void m(e);
                            switch (e.which) {
                                case H.UP:
                                case H.DOWN:
                                    return this.moveHighlight(e.which === H.UP ? -1 : 1), void m(e);
                                case H.ENTER:
                                    return this.selectHighlighted(), void m(e);
                                case H.TAB:
                                    return void this.selectHighlighted({
                                        "noFocus": !0
                                    });
                                case H.ESC:
                                    return this.cancel(e), void m(e)
                            }
                        }
                    })), this.search.on("blur", this.bind(function () {
                        document.activeElement === this.body.get(0) && window.setTimeout(this.bind(function () {
                            this.opened() && this.search.focus()
                        }), 0)
                    })), this.focusser.on("keydown", this.bind(function (e) {
                        if (this.isInterfaceEnabled() && e.which !== H.TAB && !H.isControl(e) && !H.isFunctionKey(e) && e.which !== H.ESC) {
                            if (this.opts.openOnEnter === !1 && e.which === H.ENTER) return void m(e);
                            if (e.which == H.DOWN || e.which == H.UP || e.which == H.ENTER && this.opts.openOnEnter) {
                                if (e.altKey || e.ctrlKey || e.shiftKey || e.metaKey) return;
                                return this.open(), void m(e)
                            }
                            return e.which == H.DELETE || e.which == H.BACKSPACE ? (this.opts.allowClear && this.clear(), void m(e)) : void 0
                        }
                    })), c(this.focusser), this.focusser.on("keyup-change input", this.bind(function (e) {
                        if (this.opts.minimumResultsForSearch >= 0) {
                            if (e.stopPropagation(), this.opened()) return;
                            this.open()
                        }
                    })), t.on("mousedown touchstart", "abbr", this.bind(function (e) {
                        this.isInterfaceEnabled() && (this.clear(), g(e), this.close(), this.selection && this.selection.focus())
                    })), t.on("mousedown touchstart", this.bind(function (n) {
                        i(t), this.container.hasClass("select2-container-active") || this.opts.element.trigger(e.Event("select2-focus")), this.opened() ? this.close() : this.isInterfaceEnabled() && this.open(), m(n)
                    })), r.on("mousedown touchstart", this.bind(function () {
                        this.opts.shouldFocusInput(this) && this.search.focus()
                    })), t.on("focus", this.bind(function (e) {
                        m(e)
                    })), this.focusser.on("focus", this.bind(function () {
                        this.container.hasClass("select2-container-active") || this.opts.element.trigger(e.Event("select2-focus")), this.container.addClass("select2-container-active")
                    })).on("blur", this.bind(function () {
                        this.opened() || (this.container.removeClass("select2-container-active"), this.opts.element.trigger(e.Event("select2-blur")))
                    })), this.search.on("focus", this.bind(function () {
                        this.container.hasClass("select2-container-active") || this.opts.element.trigger(e.Event("select2-focus")), this.container.addClass("select2-container-active")
                    })), this.initContainerWidth(), this.opts.element.hide(), this.setPlaceholder()
                },
                "clear": function (t) {
                    var i = this.selection.data("select2-data");
                    if (i) {
                        var n = e.Event("select2-clearing");
                        if (this.opts.element.trigger(n), n.isDefaultPrevented()) return;
                        var s = this.getPlaceholderOption();
                        this.opts.element.val(s ? s.val() : ""), this.selection.find(".select2-chosen").empty(), this.selection.removeData("select2-data"), this.setPlaceholder(), t !== !1 && (this.opts.element.trigger({
                            "type": "select2-removed",
                            "val": this.id(i),
                            "choice": i
                        }), this.triggerChange({
                            "removed": i
                        }))
                    }
                },
                "initSelection": function () {
                    if (this.isPlaceholderOptionSelected()) this.updateSelection(null), this.close(), this.setPlaceholder();
                    else {
                        var e = this;
                        this.opts.initSelection.call(null, this.opts.element, function (i) {
                            i !== t && null !== i && (e.updateSelection(i), e.close(), e.setPlaceholder(), e.nextSearchTerm = e.opts.nextSearchTerm(i, e.search.val()))
                        })
                    }
                },
                "isPlaceholderOptionSelected": function () {
                    var e;
                    return this.getPlaceholder() === t ? !1 : (e = this.getPlaceholderOption()) !== t && e.prop("selected") || "" === this.opts.element.val() || this.opts.element.val() === t || null === this.opts.element.val()
                },
                "prepareOpts": function () {
                    var t = this.parent.prepareOpts.apply(this, arguments),
                        i = this;
                    return "select" === t.element.get(0).tagName.toLowerCase() ? t.initSelection = function (e, t) {
                        var n = e.find("option").filter(function () {
                            return this.selected && !this.disabled
                        });
                        t(i.optionToData(n))
                    } : "data" in t && (t.initSelection = t.initSelection || function (i, n) {
                        var s = i.val(),
                            r = null;
                        t.query({
                            "matcher": function (e, i, n) {
                                var o = a(s, t.id(n));
                                return o && (r = n), o
                            },
                            "callback": e.isFunction(n) ? function () {
                                n(r)
                            } : e.noop
                        })
                    }), t
                },
                "getPlaceholder": function () {
                    return this.select && this.getPlaceholderOption() === t ? t : this.parent.getPlaceholder.apply(this, arguments)
                },
                "setPlaceholder": function () {
                    var e = this.getPlaceholder();
                    if (this.isPlaceholderOptionSelected() && e !== t) {
                        if (this.select && this.getPlaceholderOption() === t) return;
                        this.selection.find(".select2-chosen").html(this.opts.escapeMarkup(e)), this.selection.addClass("select2-default"), this.container.removeClass("select2-allowclear")
                    }
                },
                "postprocessResults": function (e, t, i) {
                    var n = 0,
                        s = this;
                    if (this.findHighlightableChoices().each2(function (e, t) {
                            return a(s.id(t.data("select2-data")), s.opts.element.val()) ? (n = e, !1) : void 0
                        }), i !== !1 && this.highlight(t === !0 && n >= 0 ? n : 0), t === !0) {
                        var r = this.opts.minimumResultsForSearch;
                        r >= 0 && this.showSearch(S(e.results) >= r)
                    }
                },
                "showSearch": function (t) {
                    this.showSearchInput !== t && (this.showSearchInput = t, this.dropdown.find(".select2-search").toggleClass("select2-search-hidden", !t), this.dropdown.find(".select2-search").toggleClass("select2-offscreen", !t), e(this.dropdown, this.container).toggleClass("select2-with-searchbox", t))
                },
                "onSelect": function (e, t) {
                    if (this.triggerSelect(e)) {
                        var i = this.opts.element.val(),
                            n = this.data();
                        this.opts.element.val(this.id(e)), this.updateSelection(e), this.opts.element.trigger({
                            "type": "select2-selected",
                            "val": this.id(e),
                            "choice": e
                        }), this.nextSearchTerm = this.opts.nextSearchTerm(e, this.search.val()), this.close(), t && t.noFocus || !this.opts.shouldFocusInput(this) || this.focusser.focus(), a(i, this.id(e)) || this.triggerChange({
                            "added": e,
                            "removed": n
                        })
                    }
                },
                "updateSelection": function (e) {
                    var i, n, s = this.selection.find(".select2-chosen");
                    this.selection.data("select2-data", e), s.empty(), null !== e && (i = this.opts.formatSelection(e, s, this.opts.escapeMarkup)), i !== t && s.append(i), n = this.opts.formatSelectionCssClass(e, s), n !== t && s.addClass(n), this.selection.removeClass("select2-default"), this.opts.allowClear && this.getPlaceholder() !== t && this.container.addClass("select2-allowclear")
                },
                "val": function () {
                    var e, i = !1,
                        n = null,
                        s = this,
                        r = this.data();
                    if (0 === arguments.length) return this.opts.element.val();
                    if (e = arguments[0], arguments.length > 1 && (i = arguments[1]), this.select) this.select.val(e).find("option").filter(function () {
                        return this.selected
                    }).each2(function (e, t) {
                        return n = s.optionToData(t), !1
                    }), this.updateSelection(n), this.setPlaceholder(), i && this.triggerChange({
                        "added": n,
                        "removed": r
                    });
                    else {
                        if (!e && 0 !== e) return void this.clear(i);
                        if (this.opts.initSelection === t) throw new Error("cannot call val() if initSelection() is not defined");
                        this.opts.element.val(e), this.opts.initSelection(this.opts.element, function (e) {
                            s.opts.element.val(e ? s.id(e) : ""), s.updateSelection(e), s.setPlaceholder(), i && s.triggerChange({
                                "added": e,
                                "removed": r
                            })
                        })
                    }
                },
                "clearSearch": function () {
                    this.search.val(""), this.focusser.val("")
                },
                "data": function (e) {
                    var i, n = !1;
                    return 0 === arguments.length ? (i = this.selection.data("select2-data"), i == t && (i = null), i) : (arguments.length > 1 && (n = arguments[1]), void(e ? (i = this.data(), this.opts.element.val(e ? this.id(e) : ""), this.updateSelection(e), n && this.triggerChange({
                        "added": e,
                        "removed": i
                    })) : this.clear(n)))
                }
            }), I = M(N, {
                "createContainer": function () {
                    var t = e(document.createElement("div")).attr({
                        "class": "select2-container select2-container-multi"
                    }).html(["<ul class='select2-choices'>", "  <li class='select2-search-field'>", "    <label for='' class='select2-offscreen'></label>", "    <input type='text' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' class='select2-input'>", "  </li>", "</ul>", "<div class='select2-drop select2-drop-multi select2-display-none'>", "   <ul class='select2-results'>", "   </ul>", "</div>"].join(""));
                    return t
                },
                "prepareOpts": function () {
                    var t = this.parent.prepareOpts.apply(this, arguments),
                        i = this;
                    return "select" === t.element.get(0).tagName.toLowerCase() ? t.initSelection = function (e, t) {
                        var n = [];
                        e.find("option").filter(function () {
                            return this.selected && !this.disabled
                        }).each2(function (e, t) {
                            n.push(i.optionToData(t))
                        }), t(n)
                    } : "data" in t && (t.initSelection = t.initSelection || function (i, n) {
                        var s = o(i.val(), t.separator, t.transformVal),
                            r = [];
                        t.query({
                            "matcher": function (i, n, o) {
                                var l = e.grep(s, function (e) {
                                    return a(e, t.id(o))
                                }).length;
                                return l && r.push(o), l
                            },
                            "callback": e.isFunction(n) ? function () {
                                for (var e = [], i = 0; i < s.length; i++)
                                    for (var o = s[i], l = 0; l < r.length; l++) {
                                        var c = r[l];
                                        if (a(o, t.id(c))) {
                                            e.push(c), r.splice(l, 1);
                                            break
                                        }
                                    }
                                n(e)
                            } : e.noop
                        })
                    }), t
                },
                "selectChoice": function (e) {
                    var t = this.container.find(".select2-search-choice-focus");
                    t.length && e && e[0] == t[0] || (t.length && this.opts.element.trigger("choice-deselected", t), t.removeClass("select2-search-choice-focus"), e && e.length && (this.close(), e.addClass("select2-search-choice-focus"), this.opts.element.trigger("choice-selected", e)))
                },
                "destroy": function () {
                    e("label[for='" + this.search.attr("id") + "']").attr("for", this.opts.element.attr("id")), this.parent.destroy.apply(this, arguments), E.call(this, "searchContainer", "selection")
                },
                "initContainer": function () {
                    var t, i = ".select2-choices";
                    this.searchContainer = this.container.find(".select2-search-field"), this.selection = t = this.container.find(i);
                    var n = this;
                    this.selection.on("click", ".select2-container:not(.select2-container-disabled) .select2-search-choice:not(.select2-locked)", function () {
                        n.search[0].focus(), n.selectChoice(e(this))
                    }), this.search.attr("id", "s2id_autogen" + j()), this.search.prev().text(e("label[for='" + this.opts.element.attr("id") + "']").text()).attr("for", this.search.attr("id")), this.opts.element.focus(this.bind(function () {
                        this.focus()
                    })), this.search.on("input paste", this.bind(function () {
                        this.search.attr("placeholder") && 0 == this.search.val().length || this.isInterfaceEnabled() && (this.opened() || this.open())
                    })), this.search.attr("tabindex", this.elementTabIndex), this.keydowns = 0, this.search.on("keydown", this.bind(function (e) {
                        if (this.isInterfaceEnabled()) {
                            ++this.keydowns;
                            var i = t.find(".select2-search-choice-focus"),
                                n = i.prev(".select2-search-choice:not(.select2-locked)"),
                                s = i.next(".select2-search-choice:not(.select2-locked)"),
                                r = f(this.search);
                            if (i.length && (e.which == H.LEFT || e.which == H.RIGHT || e.which == H.BACKSPACE || e.which == H.DELETE || e.which == H.ENTER)) {
                                var a = i;
                                return e.which == H.LEFT && n.length ? a = n : e.which == H.RIGHT ? a = s.length ? s : null : e.which === H.BACKSPACE ? this.unselect(i.first()) && (this.search.width(10), a = n.length ? n : s) : e.which == H.DELETE ? this.unselect(i.first()) && (this.search.width(10), a = s.length ? s : null) : e.which == H.ENTER && (a = null), this.selectChoice(a), m(e), void(a && a.length || this.open())
                            }
                            if ((e.which === H.BACKSPACE && 1 == this.keydowns || e.which == H.LEFT) && 0 == r.offset && !r.length) return this.selectChoice(t.find(".select2-search-choice:not(.select2-locked)").last()), void m(e);
                            if (this.selectChoice(null), this.opened()) switch (e.which) {
                                case H.UP:
                                case H.DOWN:
                                    return this.moveHighlight(e.which === H.UP ? -1 : 1), void m(e);
                                case H.ENTER:
                                    return this.selectHighlighted(), void m(e);
                                case H.TAB:
                                    return this.selectHighlighted({
                                        "noFocus": !0
                                    }), void this.close();
                                case H.ESC:
                                    return this.cancel(e), void m(e)
                            }
                            if (e.which !== H.TAB && !H.isControl(e) && !H.isFunctionKey(e) && e.which !== H.BACKSPACE && e.which !== H.ESC) {
                                if (e.which === H.ENTER) {
                                    if (this.opts.openOnEnter === !1) return;
                                    if (e.altKey || e.ctrlKey || e.shiftKey || e.metaKey) return
                                }
                                this.open(), (e.which === H.PAGE_UP || e.which === H.PAGE_DOWN) && m(e), e.which === H.ENTER && m(e)
                            }
                        }
                    })), this.search.on("keyup", this.bind(function () {
                        this.keydowns = 0, this.resizeSearch()
                    })), this.search.on("blur", this.bind(function (t) {
                        this.container.removeClass("select2-container-active"), this.search.removeClass("select2-focused"), this.selectChoice(null), this.opened() || this.clearSearch(), t.stopImmediatePropagation(), this.opts.element.trigger(e.Event("select2-blur"))
                    })), this.container.on("click", i, this.bind(function (t) {
                        this.isInterfaceEnabled() && (e(t.target).closest(".select2-search-choice").length > 0 || (this.selectChoice(null), this.clearPlaceholder(), this.container.hasClass("select2-container-active") || this.opts.element.trigger(e.Event("select2-focus")), this.open(), this.focusSearch(), t.preventDefault()))
                    })), this.container.on("focus", i, this.bind(function () {
                        this.isInterfaceEnabled() && (this.container.hasClass("select2-container-active") || this.opts.element.trigger(e.Event("select2-focus")), this.container.addClass("select2-container-active"), this.dropdown.addClass("select2-drop-active"), this.clearPlaceholder())
                    })), this.initContainerWidth(), this.opts.element.hide(), this.clearSearch()
                },
                "enableInterface": function () {
                    this.parent.enableInterface.apply(this, arguments) && this.search.prop("disabled", !this.isInterfaceEnabled())
                },
                "initSelection": function () {
                    if ("" === this.opts.element.val() && "" === this.opts.element.text() && (this.updateSelection([]), this.close(), this.clearSearch()), this.select || "" !== this.opts.element.val()) {
                        var e = this;
                        this.opts.initSelection.call(null, this.opts.element, function (i) {
                            i !== t && null !== i && (e.updateSelection(i), e.close(), e.clearSearch())
                        })
                    }
                },
                "clearSearch": function () {
                    var e = this.getPlaceholder(),
                        i = this.getMaxSearchWidth();
                    e !== t && 0 === this.getVal().length && this.search.hasClass("select2-focused") === !1 ? (this.search.val(e).addClass("select2-default"), this.search.width(i > 0 ? i : this.container.css("width"))) : this.search.val("").width(10)
                },
                "clearPlaceholder": function () {
                    this.search.hasClass("select2-default") && this.search.val("").removeClass("select2-default")
                },
                "opening": function () {
                    this.clearPlaceholder(), this.resizeSearch(), this.parent.opening.apply(this, arguments), this.focusSearch(), "" === this.search.val() && this.nextSearchTerm != t && (this.search.val(this.nextSearchTerm), this.search.select()), this.updateResults(!0), this.opts.shouldFocusInput(this) && this.search.focus(), this.opts.element.trigger(e.Event("select2-open"))
                },
                "close": function () {
                    this.opened() && this.parent.close.apply(this, arguments)
                },
                "focus": function () {
                    this.close(), this.search.focus()
                },
                "isFocused": function () {
                    return this.search.hasClass("select2-focused")
                },
                "updateSelection": function (t) {
                    var i = [],
                        n = [],
                        r = this;
                    e(t).each(function () {
                        s(r.id(this), i) < 0 && (i.push(r.id(this)), n.push(this))
                    }), t = n, this.selection.find(".select2-search-choice").remove(), e(t).each(function () {
                        r.addSelectedChoice(this)
                    }), r.postprocessResults()
                },
                "tokenize": function () {
                    var e = this.search.val();
                    e = this.opts.tokenizer.call(this, e, this.data(), this.bind(this.onSelect), this.opts), null != e && e != t && (this.search.val(e), e.length > 0 && this.open())
                },
                "onSelect": function (e, i) {
                    this.triggerSelect(e) && "" !== e.text && (this.addSelectedChoice(e), this.opts.element.trigger({
                        "type": "selected",
                        "val": this.id(e),
                        "choice": e
                    }), this.nextSearchTerm = this.opts.nextSearchTerm(e, this.search.val()), this.clearSearch(), this.updateResults(), (this.select || !this.opts.closeOnSelect) && this.postprocessResults(e, !1, this.opts.closeOnSelect === !0), this.opts.closeOnSelect ? (this.close(), this.search.width(10)) : this.countSelectableResults() > 0 ? (this.search.width(10), this.resizeSearch(), this.getMaximumSelectionSize() > 0 && this.val().length >= this.getMaximumSelectionSize() ? this.updateResults(!0) : this.nextSearchTerm != t && (this.search.val(this.nextSearchTerm), this.updateResults(), this.search.select()), this.positionDropdown()) : (this.close(), this.search.width(10)), this.triggerChange({
                        "added": e
                    }), i && i.noFocus || this.focusSearch())
                },
                "cancel": function () {
                    this.close(), this.focusSearch()
                },
                "addSelectedChoice": function (i) {
                    var n, s, r = !i.locked,
                        a = e("<li class='select2-search-choice'>    <div></div>    <a href='#' class='select2-search-choice-close' tabindex='-1'></a></li>"),
                        o = e("<li class='select2-search-choice select2-locked'><div></div></li>"),
                        l = r ? a : o,
                        c = this.id(i),
                        u = this.getVal();
                    n = this.opts.formatSelection(i, l.find("div"), this.opts.escapeMarkup), n != t && l.find("div").replaceWith(e("<div></div>").html(n)), s = this.opts.formatSelectionCssClass(i, l.find("div")), s != t && l.addClass(s), r && l.find(".select2-search-choice-close").on("mousedown", m).on("click dblclick", this.bind(function (t) {
                        this.isInterfaceEnabled() && (this.unselect(e(t.target)), this.selection.find(".select2-search-choice-focus").removeClass("select2-search-choice-focus"), m(t), this.close(), this.focusSearch())
                    })).on("focus", this.bind(function () {
                        this.isInterfaceEnabled() && (this.container.addClass("select2-container-active"), this.dropdown.addClass("select2-drop-active"))
                    })), l.data("select2-data", i), l.insertBefore(this.searchContainer), u.push(c), this.setVal(u)
                },
                "unselect": function (t) {
                    var i, n, r = this.getVal();
                    if (t = t.closest(".select2-search-choice"), 0 === t.length) throw "Invalid argument: " + t + ". Must be .select2-search-choice";
                    if (i = t.data("select2-data")) {
                        var a = e.Event("select2-removing");
                        if (a.val = this.id(i), a.choice = i, this.opts.element.trigger(a), a.isDefaultPrevented()) return !1;
                        for (;
                            (n = s(this.id(i), r)) >= 0;) r.splice(n, 1), this.setVal(r), this.select && this.postprocessResults();
                        return t.remove(), this.opts.element.trigger({
                            "type": "select2-removed",
                            "val": this.id(i),
                            "choice": i
                        }), this.triggerChange({
                            "removed": i
                        }), !0
                    }
                },
                "postprocessResults": function (e, t, i) {
                    var n = this.getVal(),
                        r = this.results.find(".select2-result"),
                        a = this.results.find(".select2-result-with-children"),
                        o = this;
                    r.each2(function (e, t) {
                        var i = o.id(t.data("select2-data"));
                        s(i, n) >= 0 && (t.addClass("select2-selected"), t.find(".select2-result-selectable").addClass("select2-selected"))
                    }), a.each2(function (e, t) {
                        t.is(".select2-result-selectable") || 0 !== t.find(".select2-result-selectable:not(.select2-selected)").length || t.addClass("select2-selected")
                    }), -1 == this.highlight() && i !== !1 && this.opts.closeOnSelect === !0 && o.highlight(0), !this.opts.createSearchChoice && !r.filter(".select2-result:not(.select2-selected)").length > 0 && (!e || e && !e.more && 0 === this.results.find(".select2-no-results").length) && C(o.opts.formatNoMatches, "formatNoMatches") && this.results.append("<li class='select2-no-results'>" + D(o.opts.formatNoMatches, o.opts.element, o.search.val()) + "</li>")
                },
                "getMaxSearchWidth": function () {
                    return this.selection.width() - l(this.search)
                },
                "resizeSearch": function () {
                    var e, t, i, n, s, r = l(this.search);
                    e = v(this.search) + 10, t = this.search.offset().left, i = this.selection.width(), n = this.selection.offset().left, s = i - (t - n) - r, e > s && (s = i - r), 40 > s && (s = i - r), 0 >= s && (s = e), this.search.width(Math.floor(s))
                },
                "getVal": function () {
                    var e;
                    return this.select ? (e = this.select.val(), null === e ? [] : e) : (e = this.opts.element.val(), o(e, this.opts.separator, this.opts.transformVal))
                },
                "setVal": function (t) {
                    var i;
                    this.select ? this.select.val(t) : (i = [], e(t).each(function () {
                        s(this, i) < 0 && i.push(this)
                    }), this.opts.element.val(0 === i.length ? "" : i.join(this.opts.separator)))
                },
                "buildChangeDetails": function (e, t) {
                    for (var t = t.slice(0), e = e.slice(0), i = 0; i < t.length; i++)
                        for (var n = 0; n < e.length; n++) a(this.opts.id(t[i]), this.opts.id(e[n])) && (t.splice(i, 1), i > 0 && i--, e.splice(n, 1), n--);
                    return {
                        "added": t,
                        "removed": e
                    }
                },
                "val": function (i, n) {
                    var s, r = this;
                    if (0 === arguments.length) return this.getVal();
                    if (s = this.data(), s.length || (s = []), !i && 0 !== i) return this.opts.element.val(""), this.updateSelection([]), this.clearSearch(), void(n && this.triggerChange({
                        "added": this.data(),
                        "removed": s
                    }));
                    if (this.setVal(i), this.select) this.opts.initSelection(this.select, this.bind(this.updateSelection)), n && this.triggerChange(this.buildChangeDetails(s, this.data()));
                    else {
                        if (this.opts.initSelection === t) throw new Error("val() cannot be called if initSelection() is not defined");
                        this.opts.initSelection(this.opts.element, function (t) {
                            var i = e.map(t, r.id);
                            r.setVal(i), r.updateSelection(t), r.clearSearch(), n && r.triggerChange(r.buildChangeDetails(s, r.data()))
                        })
                    }
                    this.clearSearch()
                },
                "onSortStart": function () {
                    if (this.select) throw new Error("Sorting of elements is not supported when attached to <select>. Attach to <input type='hidden'/> instead.");
                    this.search.width(0), this.searchContainer.hide()
                },
                "onSortEnd": function () {
                    var t = [],
                        i = this;
                    this.searchContainer.show(), this.searchContainer.appendTo(this.searchContainer.parent()), this.resizeSearch(), this.selection.find(".select2-search-choice").each(function () {
                        t.push(i.opts.id(e(this).data("select2-data")))
                    }), this.setVal(t), this.triggerChange()
                },
                "data": function (t, i) {
                    var n, s, r = this;
                    return 0 === arguments.length ? this.selection.children(".select2-search-choice").map(function () {
                        return e(this).data("select2-data")
                    }).get() : (s = this.data(), t || (t = []), n = e.map(t, function (e) {
                        return r.opts.id(e)
                    }), this.setVal(n), this.updateSelection(t), this.clearSearch(), i && this.triggerChange(this.buildChangeDetails(s, this.data())), void 0)
                }
            }), e.fn.select2 = function () {
                var i, n, r, a, o, l = Array.prototype.slice.call(arguments, 0),
                    c = ["val", "destroy", "opened", "open", "close", "focus", "isFocused", "container", "dropdown", "onSortStart", "onSortEnd", "enable", "disable", "readonly", "positionDropdown", "data", "search"],
                    u = ["opened", "isFocused", "container", "dropdown"],
                    h = ["val", "data"],
                    d = {
                        "search": "externalSearch"
                    };
                return this.each(function () {
                    if (0 === l.length || "object" == typeof l[0]) i = 0 === l.length ? {} : e.extend({}, l[0]), i.element = e(this), "select" === i.element.get(0).tagName.toLowerCase() ? o = i.element.prop("multiple") : (o = i.multiple || !1, "tags" in i && (i.multiple = o = !0)), n = o ? new window.Select2["class"].multi : new window.Select2["class"].single, n.init(i);
                    else {
                        if ("string" != typeof l[0]) throw "Invalid arguments to select2 plugin: " + l;
                        if (s(l[0], c) < 0) throw "Unknown method: " + l[0];
                        if (a = t, n = e(this).data("select2"), n === t) return;
                        if (r = l[0], "container" === r ? a = n.container : "dropdown" === r ? a = n.dropdown : (d[r] && (r = d[r]), a = n[r].apply(n, l.slice(1))), s(l[0], u) >= 0 || s(l[0], h) >= 0 && 1 == l.length) return !1
                    }
                }), a === t ? this : a
            }, e.fn.select2.defaults = {
                "width": "copy",
                "loadMorePadding": 0,
                "closeOnSelect": !0,
                "openOnEnter": !0,
                "containerCss": {},
                "dropdownCss": {},
                "containerCssClass": "",
                "dropdownCssClass": "",
                "formatResult": function (e, t, i, n) {
                    var s = [];
                    return b(this.text(e), i.term, s, n), s.join("")
                },
                "transformVal": function (t) {
                    return e.trim(t)
                },
                "formatSelection": function (e, i, n) {
                    return e ? n(this.text(e)) : t
                },
                "sortResults": function (e) {
                    return e
                },
                "formatResultCssClass": function (e) {
                    return e.css
                },
                "formatSelectionCssClass": function () {
                    return t
                },
                "minimumResultsForSearch": 0,
                "minimumInputLength": 0,
                "maximumInputLength": null,
                "maximumSelectionSize": 0,
                "id": function (e) {
                    return e == t ? null : e.id
                },
                "text": function (t) {
                    return t && this.data && this.data.text ? e.isFunction(this.data.text) ? this.data.text(t) : t[this.data.text] : t.text
                },
                "matcher": function (e, t) {
                    return n("" + t).toUpperCase().indexOf(n("" + e).toUpperCase()) >= 0
                },
                "separator": ",",
                "tokenSeparators": [],
                "tokenizer": T,
                "escapeMarkup": w,
                "blurOnChange": !1,
                "selectOnBlur": !1,
                "adaptContainerCssClass": function (e) {
                    return e
                },
                "adaptDropdownCssClass": function () {
                    return null
                },
                "nextSearchTerm": function () {
                    return t
                },
                "searchInputPlaceholder": "",
                "createSearchChoicePosition": "top",
                "shouldFocusInput": function (e) {
                    var t = "ontouchstart" in window || navigator.msMaxTouchPoints > 0;
                    return t && e.opts.minimumResultsForSearch < 0 ? !1 : !0
                }
            }, e.fn.select2.locales = [], e.fn.select2.locales.en = {
                "formatMatches": function (e) {
                    return 1 === e ? "One result is available, press enter to select it." : e + " results are available, use up and down arrow keys to navigate."
                },
                "formatNoMatches": function () {
                    return "No matches found"
                },
                "formatAjaxError": function () {
                    return "Loading failed"
                },
                "formatInputTooShort": function (e, t) {
                    var i = t - e.length;
                    return "Please enter " + i + " or more character" + (1 == i ? "" : "s")
                },
                "formatInputTooLong": function (e, t) {
                    var i = e.length - t;
                    return "Please delete " + i + " character" + (1 == i ? "" : "s")
                },
                "formatSelectionTooBig": function (e) {
                    return "You can only select " + e + " item" + (1 == e ? "" : "s")
                },
                "formatLoadMore": function () {
                    return "Loading more results\u2026"
                },
                "formatSearching": function () {
                    return "Searching\u2026"
                }
            }, e.extend(e.fn.select2.defaults, e.fn.select2.locales.en), e.fn.select2.ajaxDefaults = {
                "transport": e.ajax,
                "params": {
                    "type": "GET",
                    "cache": !1,
                    "dataType": "json"
                }
            }, window.Select2 = {
                "query": {
                    "ajax": x,
                    "local": k,
                    "tags": _
                },
                "util": {
                    "debounce": h,
                    "markMatch": b,
                    "escapeMarkup": w,
                    "stripDiacritics": n
                },
                "class": {
                    "abstract": N,
                    "single": A,
                    "multi": I
                }
            }
        }
    }(jQuery),
    function (e) {
        var t = {};
        e.fn.railsAutocomplete = function () {
            var i = function () {
                this.railsAutoCompleter || (this.railsAutoCompleter = new e.railsAutocomplete(this))
            };
            return t[this.selector.replace("#", "")] = arguments[0], void 0 !== e.fn.on ? $(document).on("focus", this.selector, i) : this.live("focus", i)
        }, e.railsAutocomplete = function (e) {
            _e = e, this.init(_e)
        }, e.railsAutocomplete.fn = e.railsAutocomplete.prototype = {
            "railsAutocomplete": "0.0.1"
        }, e.railsAutocomplete.fn.extend = e.railsAutocomplete.extend = e.extend, e.railsAutocomplete.fn.extend({
            "init": function (i) {
                function n(e) {
                    return e.split(i.delimiter)
                }

                function s(e) {
                    return n(e).pop().replace(/^\s+/, "")
                }
                i.delimiter = e(i).attr("data-delimiter") || null, e(i).autocomplete($.extend({
                    "source": function (t, n) {
                        e.getJSON(e(i).attr("data-autocomplete"), {
                            "term": s(t.term)
                        }, function () {
                            0 == arguments[0].length && (arguments[0] = [], arguments[0][0] = {
                                "id": "",
                                "label": "no existing match"
                            }), e(arguments[0]).each(function (t, n) {
                                var s = {};
                                s[n.id] = n, e(i).data(s)
                            }), n.apply(null, arguments)
                        })
                    },
                    "change": function (t, i) {
                        if ("" != e(e(this).attr("data-id-element")).val()) {
                            e(e(this).attr("data-id-element")).val(i.item ? i.item.id : "");
                            var n = e.parseJSON(e(this).attr("data-update-elements")),
                                s = i.item ? e(this).data(i.item.id.toString()) : {};
                            if (!n || "" != e(n.id).val())
                                for (var r in n) e(n[r]).val(i.item ? s[r] : "")
                        }
                    },
                    "search": function () {
                        var e = s(this.value);
                        return e.length < 2 ? !1 : void 0
                    },
                    "focus": function () {
                        return !1
                    },
                    "select": function (t, s) {
                        var r = n(this.value);
                        if (r.pop(), r.push(s.item.value), null != i.delimiter) r.push(""), this.value = r.join(i.delimiter);
                        else if (this.value = r.join(""), e(this).attr("data-id-element") && e(e(this).attr("data-id-element")).val(s.item.id), e(this).attr("data-update-elements")) {
                            var a = e(this).data(s.item.id.toString()),
                                o = e.parseJSON(e(this).attr("data-update-elements"));
                            for (var l in o) e(o[l]).val(a[l])
                        }
                        var c = this.value;
                        return e(this).bind("keyup.clearId", function () {
                            e(this).val().trim() != c.trim() && (e(e(this).attr("data-id-element")).val(""), e(this).unbind("keyup.clearId"))
                        }), e(i).trigger("railsAutocomplete.select", s), !1
                    }
                }, t[i.id]))
            }
        }), e(document).ready(function () {
            e("input[data-autocomplete]").railsAutocomplete()
        })
    }(jQuery),
    function (e) {
        var t = 0,
            i = function () {
                return (new Date).getTime() + t++
            },
            n = function (e) {
                return "[" + e + "]$1"
            },
            s = function (e) {
                return "_" + e + "_$1"
            };
        e(document).on("click", ".add_fields", function (t) {
            t.preventDefault();
            var r = e(this),
                a = r.data("association"),
                o = r.data("associations"),
                l = r.data("association-insertion-template"),
                c = r.data("association-insertion-method") || r.data("association-insertion-position") || "before",
                u = r.data("association-insertion-node"),
                h = r.data("association-insertion-traversal"),
                d = parseInt(r.data("count"), 10),
                p = new RegExp("\\[new_" + a + "\\](.*?\\s)", "g"),
                f = new RegExp("_new_" + a + "_(\\w*)", "g"),
                m = i(),
                g = l.replace(p, n(m)),
                v = [];
            for (g == l && (p = new RegExp("\\[new_" + o + "\\](.*?\\s)", "g"), f = new RegExp("_new_" + o + "_(\\w*)", "g"), g = l.replace(p, n(m))), g = g.replace(f, s(m)), v = [g], d = isNaN(d) ? 1 : Math.max(d, 1), d -= 1; d;) m = i(), g = l.replace(p, n(m)), g = g.replace(f, s(m)), v.push(g), d -= 1;
            u = u ? h ? r[h](u) : "this" == u ? r : e(u) : r.parent(), e.each(v, function (t, i) {
                var n = e(i);
                u.trigger("cocoon:before-insert", [n]);
                u[c](n);
                u.trigger("cocoon:after-insert", [n])
            })
        }), e(document).on("click", ".remove_fields.dynamic, .remove_fields.existing", function (t) {
            var i = e(this),
                n = i.data("wrapper-class") || "nested-fields",
                s = i.closest("." + n),
                r = s.parent();
            t.preventDefault(), r.trigger("cocoon:before-remove", [s]);
            var a = r.data("remove-timeout") || 0;
            setTimeout(function () {
                i.hasClass("dynamic") ? s.remove() : (i.prev("input[type=hidden]").val("1"), s.hide()), r.trigger("cocoon:after-remove", [s])
            }, a)
        }), e(".remove_fields.existing.destroyed").each(function () {
            var t = e(this),
                i = t.data("wrapper-class") || "nested-fields";
            t.closest("." + i).hide()
        })
    }(jQuery);