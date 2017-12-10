<template>
    <div class="thumbnail">
        <div class="form-group">
            <label >Add Comment</label>
            <textarea
                    class="form-control"
                    v-model="message"
                    placeholder="some text..."></textarea>
        </div>
        <button class="btn btn-primary" type="button" @click="addComment">Add</button>
    </div>
</template>

<script>
    import {eventBus} from '../app';

    export default {
        data() {
            return {
                message: ''
            }
        },
        props: ['comment'],
        methods: {
            addComment() {
                if(this.message == '') {
                    return;
                }

                let parentId = this.comment != undefined ? this.comment.id : 0;

                let data = {};
                data.message = this.message;
                data.parentId = parentId;

                this.message = '';

                eventBus.$emit('sendComment', data);
                eventBus.$emit('hideReplyDialog');
            },
            setOldMessage(old) {
                this.message = old;
            }
        }
    }
</script>