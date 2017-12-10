<template>
    <div class="row">

        <div class="panel panel-primary">
            <div class="panel-body">
                <app-comment
                        v-for="(comment, index) in comments"
                        key="index"
                        v-bind:comment="comment">
                </app-comment>
            </div>
        </div>

        <app-reply></app-reply>

    </div>
</template>

<script>
    import Comment from './Comment.vue';
    import Reply from './Reply.vue';
    import {eventBus} from '../app';

    export default {
        components: {
            appComment: Comment,
            appReply: Reply,
        },
        data() {
            return {
                comments: [],
            }
        },
        mounted() {
            this.fetchComments();
        },
        created() {
            eventBus.$on('sendComment', (data) => {
                this.sendComment(data);
            });
            eventBus.$on('updateComment', (data) => {
                this.updateComment(data);
            });
            eventBus.$on('removeComment', (data) => {
                this.removeComment(data);
            });
        },
        methods: {
            fetchComments() {
                this.$http.get(`/api/comments`)
                .then(response => response.json())
                .then(json =>  {
                    this.comments = json.comments;
                });
            },
            sendComment(newComment) {
                this.$http.post(`/api/comments/add`, newComment)
                        .then(response => response.json())
                        .then(json =>  {
                            if(newComment.parentId == 0) {
                                this.comments.push(json.comment);
                            } else {
                                let neededComment = this.findComment(newComment.parentId, this.comments);
                                neededComment.replies.push(json.comment);
                            }
                        });
            },
            updateComment(comment) {
                this.$http.put(`/api/comments/${comment.id}/update`, {message: comment.message})
                        .then(response => {
                            let neededComment = this.findComment(comment.id, this.comments);
                            neededComment.comment.message = comment.message;
                        });
            },
            removeComment(id) {
                this.$http.delete(`/api/comments/${id}/delete`)
                        .then(response => {
                            this.removeFromArray(id, this.comments);
                        });

                this.removeFromArray(id, this.comments);
            },
            findComment(id, comments) {
                let isFind = false;

                let neededComment;

                function recurse(comments, id) {
                    if(comments != undefined) {
                        for(let i = 0; i < comments.length; i++) {
                            if(comments[i].comment.id == id) {
                                neededComment = comments[i];
                                isFind = true;
                                break;
                            } else {
                                if(isFind) {
                                    break;
                                }

                                recurse(comments[i].replies, id);
                            }
                        }
                    }
                }

                recurse(comments, id);

                return neededComment;
            },
            removeFromArray(id, comments) {
                let isFind = false;

                function recurse(comments, id) {
                    if(comments != undefined) {
                        for(let i = 0; i < comments.length; i++) {
                            let index = comments.findIndex(x => x.comment.id == id);
                            if(index != -1) {
                                comments.splice(index, 1);
                                isFind = true;
                                break;
                            } else {
                                if(isFind) {
                                    break;
                                }

                                recurse(comments[i].replies, id);
                            }
                        }
                    }
                }

                recurse(comments, id);
            }
        }
    }
</script>