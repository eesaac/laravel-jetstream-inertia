<template>
    <div>
        <Header :href="route('post.index')" :name="`Post`" :action="`Show`" :createHref="route('post.create')"/>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-md shadow overflow-x-auto p-10">
                    <h2 class="text-3xl font-bold mb-4 border-b">{{ post.title }}</h2>
                    <div class="mb-3 whitespace-pre-wrap">

                            {{ post.body }}

                    </div>

                    <inertia-link :href="`${route('post.index')}?tag=${tag.name}`" v-if="post.tags" v-for="tag in post.tags" class="mr-2 inline-block uppercase text-blue-600">#{{ tag.name }}</inertia-link>

                    <div class="mt-10" v-if="can.update_user">
                        <inertia-link :href="route('post.edit', post.id)" class="btn-indigo">Edit</inertia-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout'
import Header from '../../Shared/Header';
export default {
    layout: AppLayout,
    components: {
        Header
    },
    props: {
        post: Object,
        can: Array,
    },
}
</script>
