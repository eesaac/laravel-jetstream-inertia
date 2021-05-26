<template>
    <div>
        <Header :href="route('post.index')" :name="`Post`" :createHref="route('post.create')"/>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-md shadow overflow-x-auto">
                    <Flash-Messages />

                    <div class="flex flex-wrap items-center justify-start p-2 border-b">
                        <div class="flex-grow w-auto shadow rounded">
                            <input v-model="form.search"
                                   class="relative w-full px-6 py-3 rounded-l focus:ring"
                                   autocomplete="off"
                                   type="text"
                                   name="search"
                                   placeholder="Search"
                            />
                        </div>
                        <button class="rounded-l-none rounded-r ml-0.5 py-3 px-5 bg-indigo-500 text-lg font-semibold text-white hover:text-gray-700 hover:bg-indigo-200" type="button" @click="reset">Reset</button>
                    </div>

                    <table class="w-full whitespace-nowrap">
                        <tr v-for="post in posts.data" :key="post.id" class="hover:bg-gray-200 focus-within:bg-gray-200 border-b">
                            <inertia-link :href="route('post.show', post.id)" class="flex items-center focus:text-indigo-500">
                                <td class="px-8 py-4 flex-auto align-middle">
                                    <p>{{ post.title }}</p>
                                    <p v-if="post.tag != ''" class="mt-1 inline-block">
                                        <inertia-link :href="`${route('post.index')}?tag=${tag.name}`" v-for="tag in post.tags" class="mr-2 inline-block uppercase text-blue-600">#{{ tag.name }}</inertia-link>
                                    </p>
                                </td>
                                <td class="flex-initial mr-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="block w-6 h-6 fill-gray-400"><polygon points="12.95 10.707 13.657 10 8 4.343 6.586 5.757 10.828 10 6.586 14.243 8 15.657 12.95 10.707"></polygon></svg>
                                </td>
                            </inertia-link>
                        </tr>

                        <tr v-if="posts.length === 0">
                            <td class="border-t px-8 py-4" colspan="4">No posts found.</td>
                        </tr>
                    </table>

                    <pagination class="my-6" :links="posts.links" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout'
import Header from '../../Shared/Header';
import FlashMessages from "../../Shared/FlashMessages";
import SearchFilter from "../../Shared/SearchFilter";
import Pagination from "../../Shared/Pagination";
import throttle from "lodash/throttle";
import pickBy from "lodash/pickBy";
import mapValues from "lodash/mapValues";

export default {
    layout: AppLayout,
    components: {
        Header,
        FlashMessages,
        SearchFilter,
        Pagination,
    },
    props: {
        posts: Object,
        filters: Object,
    },
    data() {
        return {
            form: {
                search: this.search,
            },
        }
    },
    watch: {
        form: {
            handler: throttle(function() {
                let query = pickBy(this.form)
                let route = this.route('post.index', Object.keys(query).length ? query : { remember: 'forget' })

                this.$inertia.get(route, {}, { preserveState: true })
            }, 150),
            deep: true,
        },
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null)
        },
    },
}


</script>
