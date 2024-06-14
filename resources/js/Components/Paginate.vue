<template>
    <div v-if="links.length > 3">
      <div class="flex flex-wrap -mb-1">
        <template v-for="(link, key) in links">
          <div v-if="link.url === null" :key="key"
            class="mb-1 mr-1 px-2 py-2 text-gray-400 text-sm leading-4 border rounded" v-html="link.label" />
          <Link v-else :key="`link-${key}`" :preserve-scroll="scroll"
            class="mb-1 mr-1 px-2 py-2 text-sm leading-4 text-pwd-primary-500 focus:border-pwd-info-500 hover:text-black hover:bg-pwd-info-500 border rounded "
            :class="{ 'bg-pwd-info-500 text-black': link.active }" :href="link.url" v-html="link.label" />
        </template>
      </div>
    </div>
  </template>
  
  <script>
  import { Head, Link, usePage } from "@inertiajs/vue3";
  
  export default {
    components: {
      Link,
    },
    props: {
      links: Array,
      scroll: Boolean,
    },
  }
  </script>