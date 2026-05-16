import{c as n}from"./createLucideIcon-DXMvaCNJ.js";import{c as i,C as d,D as l,R as r,u as s,F as k,g as e}from"./app-DIG-JUPW.js";/**
 * @license lucide-vue-next v0.545.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const y=n("globe-lock",[["path",{d:"M15.686 15A14.5 14.5 0 0 1 12 22a14.5 14.5 0 0 1 0-20 10 10 0 1 0 9.542 13",key:"qkt0x6"}],["path",{d:"M2 12h8.5",key:"ovaggd"}],["path",{d:"M20 6V4a2 2 0 1 0-4 0v2",key:"1of5e8"}],["rect",{width:"8",height:"5",x:"14",y:"6",rx:"1",key:"1fmf51"}]]);/**
 * @license lucide-vue-next v0.545.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const c=n("globe",[["circle",{cx:"12",cy:"12",r:"10",key:"1mglay"}],["path",{d:"M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20",key:"13o1zl"}],["path",{d:"M2 12h20",key:"9i4pu4"}]]);/**
 * @license lucide-vue-next v0.545.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const x=n("pencil-line",[["path",{d:"M13 21h8",key:"1jsn5i"}],["path",{d:"m15 5 4 4",key:"1mk7zo"}],["path",{d:"M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z",key:"1a8usu"}]]);/**
 * @license lucide-vue-next v0.545.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const w=n("rotate-ccw",[["path",{d:"M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8",key:"1357e3"}],["path",{d:"M3 3v5h5",key:"1xhq8a"}]]);/**
 * @license lucide-vue-next v0.545.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const g=n("trash",[["path",{d:"M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6",key:"miytrc"}],["path",{d:"M3 6h18",key:"d0wm0j"}],["path",{d:"M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2",key:"e791ji"}]]),p={class:"flex gap-2 justify-center"},M={__name:"ActionButtons",props:{item:Object,visibleButtons:{type:Array,default:()=>["edit","delete","status","retry"]}},emits:["edit","delete","toggleStatus","retryUpload"],setup(t,{emit:h}){const o=h;return(m,a)=>(e(),i("div",p,[t.visibleButtons.includes("edit")?(e(),i("button",{key:0,class:"bg-blue-500 text-white px-2 py-1 rounded",onClick:a[0]||(a[0]=u=>o("edit",t.item)),title:"Edit Data"},[(e(),l(r(s(x)),{class:"w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-white dark:group-hover:text-yellow-400"}))])):d("",!0),t.visibleButtons.includes("delete")?(e(),i("button",{key:1,class:"bg-red-500 text-white px-2 py-1 rounded",onClick:a[1]||(a[1]=u=>o("delete",t.item)),title:"Hapus Data"},[(e(),l(r(s(g)),{class:"w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-white dark:group-hover:text-yellow-400"}))])):d("",!0),t.visibleButtons.includes("status")?(e(),i(k,{key:2},[t.item.status_data==="private"?(e(),i("button",{key:0,class:"bg-yellow-500 text-white px-2 py-1 rounded",onClick:a[2]||(a[2]=u=>o("toggleStatus",t.item,"publik")),title:"Set Data Publik"},[(e(),l(r(s(c)),{class:"w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-white dark:group-hover:text-yellow-400"}))])):(e(),i("button",{key:1,class:"bg-yellow-500 text-white px-2 py-1 rounded",onClick:a[3]||(a[3]=u=>o("toggleStatus",t.item,"private")),title:"Set Data Private"},[(e(),l(r(s(y)),{class:"w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-white dark:group-hover:text-yellow-400"}))]))],64)):d("",!0),t.visibleButtons.includes("retry")&&t.item.status_upload!=="success"&&t.item.status_upload!=="processing"?(e(),i("button",{key:3,class:"bg-green-500 text-white px-2 py-1 rounded",onClick:a[4]||(a[4]=u=>o("retryUpload",t.item)),title:"ulangi upload"},[(e(),l(r(s(w)),{class:"w-5 h-5 text-white transition duration-75 dark:text-white group-hover:text-white dark:group-hover:text-yellow-400"}))])):d("",!0)]))}};export{w as R,g as T,M as _};
