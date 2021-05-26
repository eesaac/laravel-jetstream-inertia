<template>
    <div>
        <Header :href="route('post.index')" :name="`Post`" :action="`Edit`" :createHref="route('post.create')"/>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-md shadow overflow-x-auto p-10">

                    <Flash-Messages />

                    <form @submit.prevent="update">
                        <div class="p-8 -mb-8 flex flex-wrap">

                            <div class="flex-1 md:mr-6 pb-8 w-full md:w-3/4">
                                <label class="form-label uppercase" for="title">title</label>
                                <input v-model="form.title"
                                       id="title"
                                       class="form-input"
                                       :class="{ error: form.errors.title }" />
                                <div v-if="form.errors.title" class="form-error">{{ form.errors.title }}</div>
                            </div>

                            <div class="flex-shrink-0 pb-8 w-full md:w-1/4">
                                <label class="form-label uppercase" for="tags">tag</label>
                                <input
                                    id="tags"
                                    placeholder="Enter a Tag"
                                    class='form-input'
                                    :class="{ error: form.errors.tags }"
                                    @keydown.enter='addTag'
                                    @keydown.188='addTag'
                                    @keydown.delete='removeLastTag'
                                />
                                <div v-if="form.errors.tags" class="form-error">{{ form.errors.tags }}</div>
                            </div>

                            <div v-if="tags.length > 0" class="-mt-8 pb-8 w-full">
                                <div @click='removeTag(index)' v-for='(tag, index) in form.tags' :key='tag'  class="bg-indigo-100 cursor-pointer inline-flex items-center text-sm rounded mt-2 mr-1 overflow-hidden">
                                    <span class="ml-2 mr-1 leading-relaxed truncate max-w-xs px-1" x-text="tag">{{ tag }}</span>
                                    <span class="w-6 h-8 inline-block align-middle text-gray-500 bg-indigo-200 focus:outline-none flex justify-end items-center">
                                        <svg class="w-6 h-6 fill-current mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M15.78 14.36a1 1 0 0 1-1.42 1.42l-2.82-2.83-2.83 2.83a1 1 0 1 1-1.42-1.42l2.83-2.82L7.3 8.7a1 1 0 0 1 1.42-1.42l2.83 2.83 2.82-2.83a1 1 0 0 1 1.42 1.42l-2.83 2.83 2.83 2.82z"/></svg>
                                    </span>
                                </div>
                            </div>

                            <div class="pb-8 w-full">
                                <label class="form-label uppercase" for="body">body</label>
                                <textarea v-model="form.body"
                                          id="body"
                                          class="form-textarea h-60"
                                          :class="{ error: form.errors.body }" />
                                <div v-if="form.errors.body" class="form-error">{{ form.errors.body }}</div>
                            </div>
                        </div>
                        <div class="px-8 pb-8 flex justify-between">
                            <button v-if="can.delete_user" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Post</button>
                            <button v-if="can.update_user" type="submit"  class="btn-indigo">
                                Update Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout'
import Header from '../../Shared/Header';
import FlashMessages from "../../Shared/FlashMessages";
export default {
    layout: AppLayout,
    components: {
        Header,
        FlashMessages,
    },
    props: {
        post: Object,
        tags: Object,
        can: Array,
    },
    data() {
        return {
            form: this.$inertia.form({
                title: this.post.title,
                tags: this.tags,
                body: this.post.body,
            }),
        }
    },
    methods: {
        update() {
            this.form.put(this.route('post.update', this.post.id))
        },
        destroy() {
            if (confirm('Are you sure you want to delete this post?')) {
                this.$inertia.delete(this.route('post.destroy', this.post.id))
            }
        },
        addTag (event) {
            event.preventDefault()
            var val = event.target.value.trim().toLowerCase()


            // this.form.tags = [ ...new Set(this.form.tags) ]
            if(this.form.tags.indexOf(val) != -1) {
                event.target.value = ''
                return false
            }


            if (val.length > 0) {
                this.form.tags.push(val)
                event.target.value = ''
            }

        },
        removeTag (index) {
            this.form.tags.splice(index, 1)
        },
        removeLastTag(event) {
            if (event.target.value.length === 0) {
                this.removeTag(this.form.tags.length - 1)
            }
        },
    },
}
</script>
