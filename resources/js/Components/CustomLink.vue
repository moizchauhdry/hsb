<script setup>
defineProps({
    color: {
        type: String,
        default: 'blue',
    },
});

const linkClass = (color) => {
    return "inline-flex items-center px-2 py-1 bg-" + color + "-500 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-" + color + "-500 focus:bg-" + color + "-500 active:bg-" + color + "-500 focus:outline-none focus:ring-2 focus:ring-" + color + "-500 focus:ring-offset-2 transition ease-in-out duration-150";
};

</script>

<template>
    <a :class="linkClass(color)">
        <slot />
    </a>
</template>
