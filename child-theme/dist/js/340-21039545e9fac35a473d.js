"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[340],{2340:(n,e,i)=>{i.r(e),(0,i(9393).Z)(".widget_archive").firstShow((function(){const n=$(this).find("ul"),e=n.find("li");n.on("click",".year",(function(){$(this).find(".months").slideToggle()})),e.each((function(){const e=$(this),i=e.find("a").text(),t=i.slice(0,i.indexOf(" ")+1),s=i.slice(i.indexOf(" ")+1);0===n.find(".year-"+s).length&&n.append('<li class="year year-'+s+'"><button>'+s+'</button><ul class="months" hidden></ul></li>'),e.find("a").text(t).end().appendTo(".year-"+s+" .months")}))}))}}]);