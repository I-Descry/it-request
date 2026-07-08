<div x-data="{
    show: false,
    title: '',
    message: '',
    actionBtnText: 'Confirm',
    actionBtnColor: '#dc2626',
    formToSubmit: null,
    dispatchEvent: null,
    dispatchDetail: null,
    confirm() {
        if (this.dispatchEvent) {
            window.dispatchEvent(new CustomEvent(this.dispatchEvent, { detail: this.dispatchDetail }));
            this.show = false;
        } else if (this.formToSubmit) {
            this.formToSubmit.submit();
        }
    }
}"
@open-confirm.window="
    show = true;
    title = $event.detail.title || 'Confirm Action';
    message = $event.detail.message || 'Are you sure you want to proceed?';
    actionBtnText = $event.detail.buttonText || 'Confirm';
    actionBtnColor = $event.detail.buttonColor || '#dc2626';
    formToSubmit = $event.detail.form || null;
    dispatchEvent = $event.detail.dispatchEvent || null;
    dispatchDetail = $event.detail.dispatchDetail || null;
"
x-show="show"
style="display: none; position: fixed; inset: 0; z-index: 9999; overflow-y: auto;"
aria-labelledby="modal-title" role="dialog" aria-modal="true"
x-cloak>
    <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 16px; text-align: center;">
        
        <!-- Background overlay -->
        <div x-show="show" 
             @click="show = false" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             style="position: fixed; inset: 0; background-color: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); transition: opacity;"></div>

        <!-- Modal panel -->
        <div x-show="show" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             style="position: relative; background-color: #1e293b; border-radius: 0.5rem; text-align: left; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); transform: transition-all; width: 100%; max-width: 28rem; border: 1px solid #334155; z-index: 10000;">
            
            <div style="background-color: #1e293b; padding: 1.5rem 1.5rem 1rem;">
                <div style="display: flex; align-items: flex-start; justify-content: center;">
                    <div style="text-align: center; width: 100%;">
                        <h3 style="font-size: 1.25rem; font-weight: 700; color: #f8fafc; margin-bottom: 0.75rem;" id="modal-title" x-text="title">
                            Confirm Action
                        </h3>
                        <div style="margin-top: 0.5rem;">
                            <p style="font-size: 0.95rem; color: #94a3b8; line-height: 1.5;" x-text="message">
                                Are you sure you want to proceed?
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="background-color: #0f172a; padding: 1rem 1.5rem; display: flex; flex-direction: row-reverse; gap: 12px; border-top: 1px solid #334155;">
                <button type="button" 
                        @click="confirm()" 
                        :style="`background-color: ${actionBtnColor}; color: white; display: inline-flex; justify-content: center; align-items: center; border-radius: 0.375rem; border: none; padding: 0.5rem 1.25rem; font-size: 0.95rem; font-weight: 600; cursor: pointer; transition: opacity 0.2s;`"
                        onmouseover="this.style.opacity='0.9'"
                        onmouseout="this.style.opacity='1'">
                    <span x-text="actionBtnText"></span>
                </button>
                <button type="button" 
                        @click="show = false" 
                        style="display: inline-flex; justify-content: center; align-items: center; border-radius: 0.375rem; border: 1px solid #475569; background-color: #1e293b; padding: 0.5rem 1.25rem; font-size: 0.95rem; font-weight: 600; color: #f8fafc; cursor: pointer; transition: background-color 0.2s;"
                        onmouseover="this.style.backgroundColor='#334155'"
                        onmouseout="this.style.backgroundColor='#1e293b'">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
