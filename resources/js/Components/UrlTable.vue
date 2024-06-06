<script>

import { Transition } from 'vue';

export default {
    props: {
        items: {
            original: Array,
            required: true,
        },
    },
    methods: {
        async copyToClipboard(text,short) {


            try {
                await navigator.clipboard.writeText(text);
                this.copySuccessMessage = short;
                setTimeout(() => {
                    this.copySuccessMessage = "";
                }, 2000);
            } catch (err) {
                console.error('Failed to copy: ', err);

            }
        }
    },
    data() {
        return {
            copySuccessMessage: false
        }
    }
};


</script>

<template>
    <div
        class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
        <div class="rounded-xl overflow-hidden border-white border text-white ">
            <table class="table-fixed md:min-w-[700px] lg:max-w-md">
                <thead>
                    <tr>
                        <th class="w-1/2 px-4 py-2">Short</th>
                        <th class="w-1/4 px-4 py-2">Original</th>
                        <th class="w-1/4 px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>

                    <tr v-for="item in items">
                        <td class="border px-4 py-2">
                            <div class="flex " :key="item.short">
                                {{ item.short }}
                                <div class="w-1 pr-5" @click="copyToClipboard(item.full_url,item.short)">
                                    <svg width="800px" height="800px" viewBox="0 0 24 24" fill="#22c55e"
                                        class="w-7 h-7 stroke-red-500" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8 5.00005C7.01165 5.00082 6.49359 5.01338 6.09202 5.21799C5.71569 5.40973 5.40973 5.71569 5.21799 6.09202C5 6.51984 5 7.07989 5 8.2V17.8C5 18.9201 5 19.4802 5.21799 19.908C5.40973 20.2843 5.71569 20.5903 6.09202 20.782C6.51984 21 7.07989 21 8.2 21H15.8C16.9201 21 17.4802 21 17.908 20.782C18.2843 20.5903 18.5903 20.2843 18.782 19.908C19 19.4802 19 18.9201 19 17.8V8.2C19 7.07989 19 6.51984 18.782 6.09202C18.5903 5.71569 18.2843 5.40973 17.908 5.21799C17.5064 5.01338 16.9884 5.00082 16 5.00005M8 5.00005V7H16V5.00005M8 5.00005V4.70711C8 4.25435 8.17986 3.82014 8.5 3.5C8.82014 3.17986 9.25435 3 9.70711 3H14.2929C14.7456 3 15.1799 3.17986 15.5 3.5C15.8201 3.82014 16 4.25435 16 4.70711V5.00005M15 18C14.7164 16.8589 13.481 16 12 16C10.519 16 9.28364 16.8589 9 18M12 12H12.01M13 12C13 12.5523 12.5523 13 12 13C11.4477 13 11 12.5523 11 12C11 11.4477 11.4477 11 12 11C12.5523 11 13 11.4477 13 12Z"
                                            stroke="#000000" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                                    <p v-if="this.copySuccessMessage == item.short" class="text-sm text-gray-100">
                                        Copied!.
                                    </p>
                                </Transition>
                            </div>


                        </td>
                        <td class="border px-4 py-2">{{ item.original }}</td>
                        <td class="border px-4 py-2">{{ item.status }}</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</template>
