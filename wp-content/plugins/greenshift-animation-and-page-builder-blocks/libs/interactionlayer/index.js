let GSscrollCalcDistance=0,GSonScrollIEvents=[],GSonMouseMoveIEvents=[];if(document.body&&document.body.classList.contains("gspb-bodyfront")){let e=document.querySelectorAll("[data-gspbactions]");GSPB_Trigger_Actions("front",e)}function GSPB_Trigger_Actions(e="front",t,r=window,n=document,s=null){if(t){if(null==s){let o=new AbortController;s=o.signal}t.forEach(t=>{if(null==t)return;let o=t.getAttribute("data-gspbactions");null==o&&(o=t.querySelector("[data-gspbactions]").getAttribute("data-gspbactions"));let i=JSON.parse(o);i&&i.length&&i.forEach((o,i)=>{let l=o?.triggerData;o?.actions;let c=l?.trigger,a=l?.selector,g=[];a?(a=a.trim(),g=Array.from(n.querySelectorAll(a))):g=[t],g.length&&g.forEach(o=>{switch(c){case"on-load":gspb_trigger_inter_Actions(t,o,i,null,r,n);break;case"click":o.addEventListener("click",e=>{gspb_trigger_inter_Actions(t,o,i,e,r,n)},{capture:!0,signal:s});break;case"mouse-enter":o.addEventListener("mouseenter",e=>{gspb_trigger_inter_Actions(t,o,i,e,r,n)},{signal:s});break;case"mouse-leave":o.addEventListener("mouseleave",e=>{gspb_trigger_inter_Actions(t,o,i,e,r,n)},{signal:s});break;case"focus":o.addEventListener("focus",e=>{gspb_trigger_inter_Actions(t,o,i,e,r,n)},{signal:s});break;case"blur":o.addEventListener("blur",e=>{gspb_trigger_inter_Actions(t,o,i,e,r,n)},{signal:s});break;case"scroll-above":if("front"===e)GSonScrollIEvents.push({type:"scroll-above",pixelScrollValue:l.pixel_scroll,element:t,triggerElement:o,layerIndex:i,windowobj:r,documentobj:n});else{let a=l.pixel_scroll;n.querySelector(".interface-interface-skeleton__content")?document.querySelector(".interface-interface-skeleton__content").addEventListener("scroll",e=>{document.querySelector(".interface-interface-skeleton__content").scrollTop<a&&gspb_trigger_inter_Actions(t,o,i,e,r,n)},{capture:!0,signal:s}):r.addEventListener("scroll",e=>{r.scrollY<a&&gspb_trigger_inter_Actions(t,o,i,e,r,n)},{capture:!0,signal:s})}break;case"scroll-below":if("front"===e)GSonScrollIEvents.push({type:"scroll-below",pixelScrollValue:l.pixel_scroll,element:t,triggerElement:o,layerIndex:i,windowobj:r,documentobj:n});else{let g=l.pixel_scroll;n.querySelector(".interface-interface-skeleton__content")?document.querySelector(".interface-interface-skeleton__content").addEventListener("scroll",e=>{document.querySelector(".interface-interface-skeleton__content").scrollTop>=g&&gspb_trigger_inter_Actions(t,o,i,e,r,n)},{capture:!0,signal:s}):r.addEventListener("scroll",e=>{r.scrollY>=g&&gspb_trigger_inter_Actions(t,o,i,e,r,n)},{capture:!0,signal:s})}break;case"mouse-move":"front"===e?GSonMouseMoveIEvents.push({element:t,triggerElement:o,layerIndex:i,windowobj:r,documentobj:n}):r.addEventListener("mousemove",e=>{gspb_trigger_inter_Actions(t,o,i,e,r,n)},{signal:s});break;case"mouse-move-object":o.addEventListener("mousemove",e=>{gspb_trigger_inter_Actions(t,o,i,e,r,n)},{signal:s});break;case"on-view":new IntersectionObserver(e=>{e.forEach(e=>{e.isIntersecting&&gspb_trigger_inter_Actions(t,o,i,e,r,n)})},{threshold:.3}).observe(o);break;case"on-leave":new IntersectionObserver(e=>{e.forEach(e=>{e.isIntersecting||gspb_trigger_inter_Actions(t,o,i,e,r,n)})},{threshold:.3}).observe(o)}})})})}}if(GSonScrollIEvents.length>0){let t=GSonScrollIEvents[0].windowobj||window;t.addEventListener("scroll",e=>{let r=t.scrollY;GSonScrollIEvents.forEach(t=>{"scroll-above"===t.type?r<t.pixelScrollValue&&gspb_trigger_inter_Actions(t.element,t.triggerElement,t.layerIndex,e,t.windowobj,t.documentobj):"scroll-below"===t.type&&r>=t.pixelScrollValue&&gspb_trigger_inter_Actions(t.element,t.triggerElement,t.layerIndex,e,t.windowobj,t.documentobj)})})}function GSPBDynamicMathPlaceholders(e,t,r,n,s){if(s.indexOf("{{SCROLL}}")>-1)s=n.querySelector(".interface-interface-skeleton__content")?s.replace("{{SCROLL}}",document.querySelector(".interface-interface-skeleton__content").scrollTop):s.replace("{{SCROLL}}",r.scrollY);else if(s.indexOf("{{SCROLLVIEW}}")>-1){let o=e.getBoundingClientRect();if(o.top<r.innerHeight&&o.bottom>=0){let i=(r.innerHeight-o.top)/(r.innerHeight+o.height)*100;s=s.replace("{{SCROLLVIEW}}",i)}else s=o.bottom<0?s.replace("{{SCROLLVIEW}}",100):s.replace("{{SCROLLVIEW}}",0)}else if(s.indexOf("{{CLIENT_X}}")>-1){let l=t.clientX/r.innerWidth*100;s=s.replace("{{CLIENT_X}}",Math.min(Math.max(l,0),100))}else if(s.indexOf("{{CLIENT_Y}}")>-1){let c=t.clientY/r.innerHeight*100;s=s.replace("{{CLIENT_Y}}",Math.min(Math.max(c,0),100))}else s.indexOf("{{WIDTH}}")>-1&&(s=s.replace("{{WIDTH}}",e.offsetWidth));return s}function GSPBMathAttributeOperator(e,t,r,n,s,o,i,l){o=GSPBDynamicMathPlaceholders(e,t,r,n,o),l&&(l=GSPBDynamicMathPlaceholders(e,t,r,n,l));let c=o;return"add"===i?c=o+l:"subtract"===i?c=o-l:"multiply"===i?c=o*l:"divide"===i?c=o/l:"modulo"===i&&(c=o%l),s&&(c+=s),c}function gspb_trigger_inter_Actions(e,t,r,n,s=window,o=document){let i=e.getAttribute("data-gspbactions");null==i&&(i=e.querySelector("[data-gspbactions]").getAttribute("data-gspbactions"));let l=JSON.parse(i);if(!l||!l.length)return;let c=l[r]?.actions,a=l[r]?.triggerData?.delay,g=l[r]?.triggerData?.delaytime||0;void 0!==c&&(a&&g>0?setTimeout(()=>{gspb_execute_inter_Actions(t,c,n,s,o)},g):gspb_execute_inter_Actions(t,c,n,s,o))}function gspb_execute_inter_Actions(e,t,r,n=window,s=document){if(void 0!==t)for(let o of t){let i=o?.env;if("no-action"===i&&!s.body.classList.contains("gspb-bodyfront"))return;let l=o?.actionname,c=o?.selector,a=o?.conditions,g="",d=o?.classname,b=o?.attr,u=o?.attrValue,p=o?.attrMath,f=o?.attrValue2,m=o?.attrUnit,h=[];if(c){if(c=c.trim(),e.classList&&e.classList.contains("gspb-buttonbox")&&(e=e.classList.contains("wp-block-greenshift-blocks-buttonbox")?e.closest(".gspb_button_wrapper"):e.closest(".wp-block-greenshift-blocks-buttonbox")),c.includes("{CLOSEST")){let v=c.match(/\{CLOSEST:(.*?)\}/)?.[1],S=c.match(/\{SELECTOR_ALL:(.*?)\}/)?.[1];v&&S&&(S=S.replace("{TRIGGERINDEX}",Array.from(e.parentNode.children).indexOf(e)),h=Array.from(e.closest(v).querySelectorAll(S)))}else c=c.replace("{TRIGGERINDEX}",Array.from(e.parentNode.children).indexOf(e)+1),h=Array.from(s.querySelectorAll(c))}else h=[e];if(!h.length)return;h.forEach(t=>{if("attach-class"===l&&(a?!0===(g=gspb_check_inter_Conditions(e,a))&&t.classList.add(d):t.classList.add(d)),"attach-attribute"===l&&(a?!0===(g=gspb_check_inter_Conditions(e,a))&&t.setAttribute(b,GSPBMathAttributeOperator(t,r,n,s,m,u,p,f)):t.setAttribute(b,GSPBMathAttributeOperator(t,r,n,s,m,u,p,f))),"set-variable"===l&&(a?!0===(g=gspb_check_inter_Conditions(e,a))&&t.style.setProperty(b,GSPBMathAttributeOperator(t,r,n,s,m,u,p,f)):t.style.setProperty(b,GSPBMathAttributeOperator(t,r,n,s,m,u,p,f))),"toggle-class"===l&&(a?!0===(g=gspb_check_inter_Conditions(e,a))&&t.classList.toggle(d):t.classList.toggle(d)),"remove-class"===l&&(a?!0===(g=gspb_check_inter_Conditions(e,a))&&t.classList&&t.classList.remove(d):t.classList&&t.classList.remove(d)),"remove-attribute"===l&&(a?!0===(g=gspb_check_inter_Conditions(e,a))&&t.removeAttribute(b):t.removeAttribute(b)),"save-to-browser-storage"===l){let i=o.storagekey,c=o.storagevalue;localStorage.setItem(i,c)}if("remove-from-browser-storage"===l){let h=o.storagekey;localStorage.removeItem(h)}"hide-element"===l&&(t.style.display="none"),"show-element"===l&&(t.style.display="block"),"toggle-element"===l&&("none"===t.style.display?t.style.display="block":t.style.display="none")})}}function gspb_check_inter_Conditions(e,t){let r=!1;return t&&t.forEach(t=>{let n=t.includeornot,s=t.classorid,o=t.additionalclass;"includes"===n?r=!!("class"===s&&e.classList.contains(o))||"id"===s&&e.id===o:"not-includes"===n&&(r=!("class"!==s||e.classList.contains(o))||"id"===s&&e.id!==o)}),r}GSonMouseMoveIEvents.length>0&&(GSonMouseMoveIEvents[0].windowobj||window).addEventListener("mousemove",e=>{GSonMouseMoveIEvents.forEach(t=>{gspb_trigger_inter_Actions(t.element,t.triggerElement,t.layerIndex,e,t.windowobj,t.documentobj)})});