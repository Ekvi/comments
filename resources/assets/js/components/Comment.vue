<template>
    <div class="comment">
        <div class="pull-right">
            <i class="fa fa-pencil" @click="edit"></i>
            <i class="fa fa-trash text-danger" @click="remove"></i>
        </div>
        <p>{{comment.comment.message}}</p>
        <a href="#" @click.prevent="replyDialog = !replyDialog">Reply</a>
        <app-reply
                v-if="replyDialog"
                v-bind:comment="comment.comment">
        </app-reply>
        <app-edit
                v-if="editComment"
                v-bind:id="comment.comment.id"
                v-bind:editMessage="comment.comment.message">
        </app-edit>
        <app-comment
                v-for="(comment, index) in comment.replies"
                key="index"
                v-bind:comment="comment">
        </app-comment>
    </div>

</template>

<script>
    import Reply from './Reply.vue';
    import Edit from './Edit.vue';
    import Comment from './Comment.vue';
    import {eventBus} from '../app';

    export default {
        name: 'app-comment',
        components: {
            appReply: Reply,
            appEdit: Edit,
        },
        data() {
            return {
                replyDialog: false,
                editComment: false
            }
        },
        props: ['comment'],
        created() {
            eventBus.$on('hideReplyDialog', () => {
                this.replyDialog = false;
            });

            eventBus.$on('hideEditCommentDialog', () => {
                this.editComment = false;
            });
        },
        methods: {
            edit() {
                this.editComment = !this.editComment;

            },
            remove() {
                eventBus.$emit('removeComment', this.comment.comment.id);
            }
        }
    }
</script>

<style>
    .comment {
        margin-left: 20px;
        margin-top: 10px;
    }
</style>