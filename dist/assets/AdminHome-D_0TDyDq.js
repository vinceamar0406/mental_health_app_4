import{_ as y}from"./AuthenticatedLayout-Bo52xLDn.js";import{c as l,o as r,a as n,u as d,m as k,w as i,b as t,F as c,r as g,P as x,d as v,e as b,t as p}from"./app-C7YuJVyk.js";import{c as o}from"./createLucideIcon-BZXc5GRo.js";import"./sweetalert2.esm.all-B0Dix5B2.js";/**
 * @license lucide-vue-next v0.483.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const _=o("CalendarIcon",[["path",{d:"M8 2v4",key:"1cmpym"}],["path",{d:"M16 2v4",key:"4m81vk"}],["rect",{width:"18",height:"18",x:"3",y:"4",rx:"2",key:"1hopcy"}],["path",{d:"M3 10h18",key:"8toen8"}]]);/**
 * @license lucide-vue-next v0.483.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const f=o("ChartColumnIcon",[["path",{d:"M3 3v16a2 2 0 0 0 2 2h16",key:"c24i48"}],["path",{d:"M18 17V9",key:"2bz60n"}],["path",{d:"M13 17V5",key:"1frdt8"}],["path",{d:"M8 17v-3",key:"17ska0"}]]);/**
 * @license lucide-vue-next v0.483.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const M=o("FileTextIcon",[["path",{d:"M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z",key:"1rqfz7"}],["path",{d:"M14 2v4a2 2 0 0 0 2 2h4",key:"tnqrlb"}],["path",{d:"M10 9H8",key:"b1mrlr"}],["path",{d:"M16 13H8",key:"t4e002"}],["path",{d:"M16 17H8",key:"z1uh3a"}]]);/**
 * @license lucide-vue-next v0.483.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const A=o("UsersIcon",[["path",{d:"M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2",key:"1yyitq"}],["circle",{cx:"9",cy:"7",r:"4",key:"nufk8"}],["path",{d:"M22 21v-2a4 4 0 0 0-3-3.87",key:"kshegd"}],["path",{d:"M16 3.13a4 4 0 0 1 0 7.75",key:"1da9ce"}]]),w={class:"p-6"},C={class:"grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6"},H={class:"p-4 shadow-lg rounded-xl bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition"},N={class:"flex items-center space-x-4"},U={class:"text-lg font-semibold text-gray-900 dark:text-white"},I={class:"text-xl font-bold text-gray-700 dark:text-gray-300"},q={__name:"AdminHome",props:{totalUsers:Number,totalAssessments:Number,totalAppointments:Number,totalReports:Number},setup(m){const e=m,h=[{title:"Total Users",value:e.totalUsers,icon:A,route:route("admin.users")},{title:"Assessments Taken",value:e.totalAssessments,icon:M,route:route("admin.assessments")},{title:"Upcoming Appointments",value:e.totalAppointments,icon:_,route:route("admin.appointments")},{title:"Reports Generated",value:e.totalReports,icon:f,route:route("admin.reports")}];return(V,s)=>(r(),l(c,null,[n(d(k),{title:"Admin Dashboard"}),n(y,null,{default:i(()=>[t("div",w,[s[0]||(s[0]=t("h1",{class:"text-3xl font-bold text-gray-900 dark:text-white"},"Admin Dashboard",-1)),s[1]||(s[1]=t("p",{class:"text-gray-600 dark:text-gray-400"},"Manage users, assessments, and appointments.",-1)),t("div",C,[(r(),l(c,null,g(h,(a,u)=>n(d(x),{key:u,href:a.route,class:"block"},{default:i(()=>[t("div",H,[t("div",N,[(r(),v(b(a.icon),{class:"w-10 h-10 text-blue-500"})),t("div",null,[t("h3",U,p(a.title),1),t("p",I,p(a.value),1)])])])]),_:2},1032,["href"])),64))])])]),_:1})],64))}};export{q as default};
