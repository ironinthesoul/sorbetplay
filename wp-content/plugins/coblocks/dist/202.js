"use strict";(self.webpackChunkcoblocks=self.webpackChunkcoblocks||[]).push([[202],{6202:function(e,o,l){l.r(o);var a=l(9196),n=l(2819),t=l(8089),c=l(5736),r=l(4333),s=l(2175),i=l(5609);o.default=(0,r.compose)([t.Z])((e=>{const{className:o,attributes:l,setAttributes:t,setBackgroundColor:r,setBlockBackgroundColor:b,setTextColor:u,fallbackTextColor:k,backgroundColor:C,blockBackgroundColor:g,textColor:m,fallbackBackgroundColor:_}=e,{hasColors:d,borderRadius:h,size:p,iconSize:v,padding:T,facebook:E,twitter:x,instagram:f,tiktok:z,pinterest:B,linkedin:y,youtube:I,yelp:w,houzz:S,opensInNewTab:R}=l,N=(0,n.includes)(o,"is-style-mask"),P=(0,n.includes)(o,"is-style-text"),L=(0,n.includes)(o,"is-style-circular"),O=[{value:"sml",label:(0,c.__)("Small","coblocks")},{value:"med",label:(0,c.__)("Medium","coblocks")},{value:"lrg",label:(0,c.__)("Large","coblocks")}],F=[{value:g.color,onChange:b,label:(0,c.__)("Background color","coblocks")},{value:C.color,onChange:r,label:(0,c.__)("Button Color","coblocks")},{value:m.color,onChange:u,label:P?(0,c.__)("Text color","coblocks"):(0,c.__)("Icon color","coblocks")}],Y=[{value:g.color,onChange:b,label:(0,c.__)("Background color","coblocks")},{value:C.color,onChange:r,label:(0,c.__)("Icon color","coblocks")}];return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(s.InspectorControls,null,(0,a.createElement)(i.PanelBody,{title:(0,c.__)("Icon settings","coblocks")},(0,a.createElement)(i.ToggleControl,{label:(0,c.__)("Social colors","coblocks"),checked:!!d,onChange:()=>t({hasColors:!d}),help:e=>e?(0,c.__)("Share button colors are enabled.","coblocks"):(0,c.__)("Toggle to use official colors from each social media platform.","coblocks")}),!N&&!L&&(0,a.createElement)(i.RangeControl,{label:(0,c.__)("Rounded corners","coblocks"),value:h,onChange:e=>t({borderRadius:e}),min:0,max:50}),(N||L)&&(0,a.createElement)(i.RangeControl,{label:(0,c.__)("Icon size","coblocks"),value:v,onChange:e=>t({iconSize:e}),min:16,max:60}),L&&(0,a.createElement)(i.RangeControl,{label:(0,c.__)("Circle size","coblocks"),value:T,onChange:e=>t({padding:e}),min:10,max:50}),!N&&!L&&(0,a.createElement)(i.SelectControl,{label:(0,c.__)("Button size","coblocks"),value:p,options:O,onChange:e=>t({size:e}),className:"components-coblocks-inspector__social-button-size"}),(0,a.createElement)(i.ToggleControl,{label:(0,c.__)("Open links in new tab","coblocks"),checked:!!R,onChange:()=>t({opensInNewTab:!R})})),(0,a.createElement)(i.PanelBody,{title:(0,c.__)("Profiles","coblocks"),initialOpen:!1},(0,a.createElement)("div",{className:"components-social-links-list"},(0,a.createElement)(i.TextControl,{label:"Facebook",value:E,onChange:e=>t({facebook:(0,n.escape)(e)})}),(0,a.createElement)(i.TextControl,{label:"Twitter",value:x,onChange:e=>t({twitter:(0,n.escape)(e)})}),(0,a.createElement)(i.TextControl,{label:"Instagram",value:f,onChange:e=>t({instagram:(0,n.escape)(e)})}),(0,a.createElement)(i.TextControl,{label:"TikTok",value:z,onChange:e=>t({tiktok:(0,n.escape)(e)})}),(0,a.createElement)(i.TextControl,{label:"Pinterest",value:B,onChange:e=>t({pinterest:(0,n.escape)(e)})}),(0,a.createElement)(i.TextControl,{label:"LinkedIn",value:y,onChange:e=>t({linkedin:(0,n.escape)(e)})}),(0,a.createElement)(i.TextControl,{label:"YouTube",value:I,onChange:e=>t({youtube:(0,n.escape)(e)})}),(0,a.createElement)(i.TextControl,{label:"Yelp",value:w,onChange:e=>t({yelp:(0,n.escape)(e)})}),(0,a.createElement)(i.TextControl,{label:"Houzz",value:S,onChange:e=>t({houzz:(0,n.escape)(e)})}))),!d&&(0,a.createElement)(s.PanelColorSettings,{title:(0,c.__)("Color settings","coblocks"),initialOpen:!1,colorSettings:N?Y:F},!N&&(0,a.createElement)(s.ContrastChecker,{isLargeText:!0,textColor:m.color,backgroundColor:C.color,fallbackBackgroundColor:_,fallbackTextColor:k}))))}))}}]);