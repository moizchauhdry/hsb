import{v as n,e as l,w as t,o as d,a,u as o,X as c,b as e,f,n as p,g as u}from"./app-9be5a561.js";import{_}from"./GuestLayout-94247b47.js";import{_ as w}from"./InputError-ef5b7e2a.js";import{_ as b}from"./InputLabel-bf84489d.js";import{_ as g}from"./PrimaryButton-c1e17f0b.js";import{_ as h}from"./TextInput-a4756595.js";import"./_plugin-vue_export-helper-c27b6911.js";const x=e("div",{class:"mb-4 text-sm text-gray-600"}," This is a secure area of the application. Please confirm your password before continuing. ",-1),v=["onSubmit"],V={class:"flex justify-end mt-4"},j={__name:"ConfirmPassword",setup(y){const s=n({password:""}),i=()=>{s.post(route("password.confirm"),{onFinish:()=>s.reset()})};return(C,r)=>(d(),l(_,null,{default:t(()=>[a(o(c),{title:"Confirm Password"}),x,e("form",{onSubmit:u(i,["prevent"])},[e("div",null,[a(b,{for:"password",value:"Password"}),a(h,{id:"password",type:"password",class:"mt-1 block w-full",modelValue:o(s).password,"onUpdate:modelValue":r[0]||(r[0]=m=>o(s).password=m),required:"",autocomplete:"current-password",autofocus:""},null,8,["modelValue"]),a(w,{class:"mt-2",message:o(s).errors.password},null,8,["message"])]),e("div",V,[a(g,{class:p(["ml-4",{"opacity-25":o(s).processing}]),disabled:o(s).processing},{default:t(()=>[f(" Confirm ")]),_:1},8,["class","disabled"])])],40,v)]),_:1}))}};export{j as default};
