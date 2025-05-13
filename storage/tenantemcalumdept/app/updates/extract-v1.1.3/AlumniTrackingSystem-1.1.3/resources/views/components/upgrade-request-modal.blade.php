<!-- Upgrade Request Modal Component -->
<div
    x-data="upgradeRequestModal"
    x-cloak
    @open-upgrade-modal.window="openModal($event.detail)"
    class="fixed inset-0 z-50 overflow-y-auto"
    :class="{ 'hidden': !open }"
>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50"
            @click="close"
            aria-hidden="true"
        ></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
            @click.away="close"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-headline"
        >
            <div>
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-headline" x-text="title"></h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500" x-text="message"></p>
                    </div>
                    
                    <form :action="formAction" method="POST" class="mt-4">
                        @csrf
                        <div class="mb-4">
                            <label for="request_details" class="block text-sm font-medium text-gray-700 text-left mb-1">
                                Why do you need to upgrade? (optional)
                            </label>
                            <textarea
                                id="request_details"
                                name="request_details"
                                rows="3"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Please let us know your specific needs or questions regarding the upgrade."
                            ></textarea>
                        </div>
                        
                        <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3">
                            <button
                                type="submit"
                                class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                Submit Request
                            </button>
                            <button
                                type="button"
                                class="inline-flex justify-center w-full px-4 py-2 mt-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0"
                                @click="close()"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('upgradeRequestModal', () => ({
            open: false,
            title: 'Upgrade Your Plan',
            message: 'Submit a request to upgrade your current plan.',
            planType: 'basic',
            formAction: '',
            
            init() {
                this.$watch('open', (value) => {
                    if (value) {
                        document.body.classList.add('overflow-hidden');
                    } else {
                        document.body.classList.remove('overflow-hidden');
                    }
                });
            },
            
            openModal(options) {
                if (options) {
                    this.title = options.title || this.title;
                    this.message = options.message || this.message;
                    this.planType = options.planType || 'basic';
                    this.formAction = `/plan-upgrade/${this.planType}`;
                }
                this.open = true;
            },
            
            close() {
                this.open = false;
            }
        }));
    });
</script> 