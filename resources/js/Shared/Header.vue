<template>
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <inertia-link v-if="createHref" class="float-right btn-indigo" :href="createHref">
                <span>Create</span>
            </inertia-link>
            <ul class="breadcrumb">
                <li v-if="href">
                    <inertia-link class="text-indigo-600 hover:text-indigo-800" :href="href">{{ name }}</inertia-link>
                </li>
                <li v-else>{{ name }}</li>
                <li v-if="action">{{ action }}</li>
            </ul>
        </div>
    </header>
</template>

<script>
export default {
    props: {
        createHref: String,
        href: String,
        name: String,
        action: String,
    },
}
</script>
