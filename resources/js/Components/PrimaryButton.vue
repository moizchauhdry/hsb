<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center px-3 py-2 bg-[#f6b514] border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-[#f6b514] focus:bg-[#f6b514] active:bg-[#f6b514] focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
