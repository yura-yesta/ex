"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[146],{6146:(t,e,i)=>{i.r(e),(0,i(9393).Z)(".widget_categories").firstShow((function(){const t=$(this).find("li");if(t.length>5){const e=$(this).find("ul"),i=$('<button class="btn">'+themeI18n.more+"</button>"),n=e.height(),h=t.filter(":nth-child(n+6)").find("a");let s=0;t.filter(":nth-child(-n+5)").each((function(){s+=$(this).height()}));let c=!0;function f(){c?(e.height(s),h.attr("tabindex",-1)):(e.height(n),h.attr("tabindex",0),i.text(themeI18n.less),h.eq(0).trigger("focus")),c=!c}e.after(i),i.on("click",f),f()}}))}}]);