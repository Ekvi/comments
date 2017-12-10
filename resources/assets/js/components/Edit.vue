<template>
    <div class="thumbnail">
        <div class="form-group">
            <label >Edit comment</label>
            <textarea
                    class="form-control"
                    v-model="message"
                    placeholder="some text..."></textarea>
        </div>
        <button class="btn btn-primary" type="button" @click="updateComment">Update</button>
    </div>
</template>

<script>
    import {eventBus} from '../app';

    export default {
        data() {
            return {
                message: this.editMessage
            }
        },
        props: ['id', 'editMessage'],
        methods: {
            updateComment() {
                let data = {};
                data.id = this.id;
                data.message = this.message;

                eventBus.$emit('updateComment', data);
                eventBus.$emit('hideEditCommentDialog');
            }
        }
    }
</script>