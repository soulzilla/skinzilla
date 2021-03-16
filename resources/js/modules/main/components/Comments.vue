<template>
    <div>
        <h3>Обсуждение</h3>
        <template v-if="load">
            <b-card class="shadow">
                <b-card-body>
                    <div class="text-center">
                        <b-spinner style="width: 3rem; height: 3rem;"></b-spinner>
                    </div>
                </b-card-body>
            </b-card>
        </template>
        <template v-else>
            <div v-for="comment of comments" v-bind:key="comment.id">
                <b-card class="shadow mt-3" footer-tag="footer" body-class="pb-0" :id="'comment-' + comment.id">
                    <template v-if="comment.blocked">
                        <div class="alert alert-secondary">
                            Комментарий заблокирован по причине: {{ comment.block_reason }}
                        </div>
                    </template>
                    <template v-else-if="comment.deleted_at">
                        <div class="alert alert-secondary">
                            Комментарий удалён автором
                        </div>
                    </template>
                    <template v-else>
                        <b-media>
                            <div class="row">
                                <h5 class="mt-0 ml-3">
                                    {{ comment.user.name }}
                                    <small v-if="entity_author && (entity_author === comment.user.id)">
                                        <b-badge variant="info">Автор сета</b-badge>
                                    </small>
                                    <small>{{ GlobalFormatTime(comment.created_at) }}</small>
                                </h5>
                                <div class="ml-auto mr-3" v-if="canDelete(comment)">
                                    <b-link @click="handleDelete(comment)">
                                        <b-icon-x></b-icon-x>
                                    </b-link>
                                </div>
                            </div>
                            <p>{{ comment.content }}</p>
                        </b-media>
                    </template>
                    <template #footer v-if="!comment.deleted_at && !comment.blocked">
                        <div class="row">
                            <div class="ml-3">
                                <b-link class="text-decoration-none text-danger" @click="toggleLike(comment)">
                                    <template v-if="comment.like">
                                        <b-icon-heart-fill></b-icon-heart-fill>
                                    </template>
                                    <template v-else>
                                        <b-icon-heart></b-icon-heart>
                                    </template>
                                    <span>{{ comment.likes_count }}</span>
                                </b-link>
                            </div>
                            <div class="ml-auto mr-3">
                                <template v-if="comment.id === currentReplyCommentId">
                                    <b-link class="text-decoration-none text-info" @click="handleHideReply">
                                        <b-icon-arrow-bar-up></b-icon-arrow-bar-up> Скрыть
                                    </b-link>
                                </template>
                                <template v-else>
                                    <b-link
                                        class="text-decoration-none text-info"
                                        @click="handleReply(comment)">
                                        <b-icon-reply></b-icon-reply> Ответить
                                    </b-link>
                                </template>
                            </div>
                        </div>
                    </template>
                </b-card>

                <div class="ml-5 mt-3 collapsed-branch" :id="'branch-' + comment.id" v-if="comment.branch">
                    <template v-for="child of comment.branch">
                        <b-card class="shadow mt-3" footer-tag="footer" v-bind:key="child.id" body-class="pb-0" :id="'comment-' + child.id">
                            <template v-if="child.blocked">
                                <div class="alert alert-secondary">
                                    Комментарий заблокирован по причине: {{ child.block_reason }}
                                </div>
                            </template>
                            <template v-else-if="child.deleted_at">
                                <div class="alert alert-secondary">
                                    Комментарий удалён автором
                                </div>
                            </template>
                            <template v-else>
                                <b-media>
                                    <div class="row">
                                        <h5 class="mt-0 ml-3">
                                            {{ child.user.name }}
                                            <small v-if="entity_author && (entity_author === child.user.id)">
                                                <b-badge variant="info">Автор сета</b-badge>
                                            </small>
                                            <small>
                                                {{ GlobalFormatTime(child.created_at) }}
                                                ответ на
                                                <b-link v-scroll-to="{
                                                el: '#comment-'+ child.parent_id,
                                                offset: -300,
                                                onDone: scrollDone,
                                            }">{{ child.parent.user.name }}</b-link>
                                            </small>
                                        </h5>
                                        <div class="ml-auto mr-3" v-if="canDelete(child)">
                                            <b-link @click="handleDelete(child)">
                                                <b-icon-x></b-icon-x>
                                            </b-link>
                                        </div>
                                    </div>
                                    <p>{{ child.content }}</p>
                                </b-media>
                            </template>
                            <template #footer v-if="!child.deleted_at && !child.blocked">
                                <div class="row">
                                    <div class="ml-3">
                                        <b-link class="text-decoration-none text-danger" @click="toggleLike(child)">
                                            <template v-if="child.like">
                                                <b-icon-heart-fill></b-icon-heart-fill>
                                            </template>
                                            <template v-else>
                                                <b-icon-heart></b-icon-heart>
                                            </template>
                                            <span>{{ child.likes_count }}</span>
                                        </b-link>
                                    </div>
                                    <div class="ml-auto mr-3">
                                        <template v-if="child.id === currentReplyCommentId">
                                            <b-link class="text-decoration-none text-info" @click="handleHideReply">
                                                <b-icon-arrow-bar-up></b-icon-arrow-bar-up> Скрыть
                                            </b-link>
                                        </template>
                                        <template v-else>
                                            <b-link class="text-decoration-none text-info" @click="handleReply(child)">
                                                <b-icon-reply></b-icon-reply> Ответить
                                            </b-link>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </b-card>
                        <div class="ml-5 mt-3" v-if="currentReplyCommentId === child.id && canComment">
                            <transition name="fade" mode="out-in" appear>
                                <div>
                                    <b-textarea placeholder="Напишите свой ответ" @input="handleReplyChange" no-resize rows="3" class="reply-text"></b-textarea>
                                    <b-button :disabled="replyDisabled" type="submit" @click="addReply(comment, child)" variant="outline-primary" class="mt-3">
                                        <i class="fa fa-paper-plane"></i> Отправить
                                    </b-button>
                                </div>
                            </transition>
                        </div>
                    </template>
                    <template v-if="comment.branch.length > 3">
                        <div class="mt-3">
                            <template v-if="currentExpanded === comment.id">
                                <b-link @click="hideChildren(comment)" class="btn btn-outline-primary btn-block">
                                    Скрыть ответы
                                </b-link>
                            </template>
                            <template v-else>
                                <b-link @click="expandChildren(comment)" class="btn btn-outline-primary btn-block">
                                    Показать все ответы
                                </b-link>
                            </template>
                        </div>
                    </template>
                </div>

                <div class="ml-5 mt-3" v-if="currentReplyCommentId === comment.id && canComment">
                    <transition name="fade" mode="out-in" appear>
                        <div :id="'reply-' + comment.id">
                            <b-textarea placeholder="Напишите свой ответ" @input="handleReplyChange" no-resize rows="3" class="reply-text"></b-textarea>
                            <b-button :disabled="replyDisabled" type="submit" @click="addReply(comment)" variant="outline-primary" class="mt-3">
                                <i class="fa fa-paper-plane"></i> Отправить
                            </b-button>
                        </div>
                    </transition>
                </div>
            </div>
            <div v-if="nextPage" class="mt-3">
                <b-link class="btn btn-outline-primary btn-block" @click="loadComments">
                    Загрузить ещё
                </b-link>
            </div>
        </template>

        <template v-if="canComment">
            <div class="mt-3">
                <b-textarea placeholder="Напишите свой комментарий" @input="handleChange" no-resize rows="5" class="comment-text"></b-textarea>
                <b-button :disabled="disabled" type="submit" @click="handleSubmit" variant="outline-primary" class="mt-3">
                    <i class="fa fa-paper-plane"></i> Отправить
                </b-button>
            </div>
        </template>
    </div>
</template>

<script>
import commentApi from '../../comment/api'
import axios from "axios";

export default {
    name: "Comments",
    props: {
        entity_id: null,
        entity_table: '',
        entity_author: null
    },
    methods: {
        handleChange: function (value) {
            this.currentComment = value

            this.disabled = !value.length;
        },
        handleSubmit: function () {
            let that = this;
            let data = {
                entity_id: this.entity_id,
                entity_table: this.entity_table,
                content: this.currentComment
            }

            commentApi.create(data).then(function (response) {
                that.comments.push(response.data.data)
                that.currentComment = ''
                that.disabled = true
                document.getElementsByClassName('comment-text')[0].value = ''
            })
        },
        handleReply: function (comment) {
            this.currentReplyCommentId = comment.id
            this.replyDisabled = true
            let that = this;
            if (!comment.parent_id) {
                setTimeout(function () {
                    that.$scrollTo('#reply-'+comment.id, 500, {
                        offset: -300,
                        onDone: that.scrollDone
                    })
                }, 1000)
            }
        },
        handleReplyChange: function (value) {
            this.currentReplyCommentText = value

            this.replyDisabled = !value.length;
        },
        handleHideReply: function () {
            this.currentReplyCommentId = null
        },
        addReply(comment, child) {
            let data = {};
            if (child) {
                data = {
                    entity_id: this.entity_id,
                    entity_table: this.entity_table,
                    content: this.currentReplyCommentText,
                    parent_id: child.id,
                    branch_id: comment.id
                }
            } else {
                data = {
                    entity_id: this.entity_id,
                    entity_table: this.entity_table,
                    content: this.currentReplyCommentText,
                    parent_id: comment.id,
                    branch_id: comment.id
                }
            }
            let that = this

            commentApi.create(data).then(function (response) {
                for (let item of that.comments) {
                    if (item.id === comment.id) {
                        if (item.branch) {
                            item.branch.push(response.data.data)
                        } else {
                            item.branch = []
                            item.branch.push(response.data.data)
                        }
                    }
                }

                that.currentReplyCommentText = ''
                that.currentReplyCommentId = null
                that.replyDisabled = true
                document.getElementsByClassName('reply-text')[0].value = ''
            })
        },
        toggleLike: function (comment) {
            if (comment.like) {
                this.dislikeIt(comment)
            } else {
                this.likeIt(comment)
            }
        },
        dislikeIt: function (comment) {
            let data = {
                entity_table: 'comments',
                entity_id: comment.id
            }
            axios.post('/dislike', data).then(function (response) {
                comment.like = null
                comment.likes_count = response.data.likes_count
            })
        },
        likeIt: function (comment) {
            let data = {
                entity_table: 'comments',
                entity_id: comment.id
            }
            axios.post('/like', data).then(function (response) {
                comment.like = response.data.data
                comment.likes_count = response.data.likes_count
            })
        },
        scrollDone: function (element) {
            element.classList.add('select-area')
            setTimeout(function () {
                element.classList.add('unselect-area')
                setTimeout(function () {
                    element.classList.remove('select-area')
                    element.classList.remove('unselect-area')
                }, 1000)
            }, 3000)
        },
        loadComments: function () {
            let that = this
            axios.get(this.nextPage,{ params: {
                    entity_id: this.entity_id,
                    entity_table: this.entity_table
                }
            }).then(function (response) {
                for (let comment of response.data.data) {
                    that.comments.push(comment)
                }
                that.nextPage = response.data.links.next
            })
        },
        expandChildren: function (comment) {
            this.currentExpanded = comment.id
            let branchContainer = document.getElementById('branch-' + comment.id)

            branchContainer.classList.remove('collapsed-branch')
        },
        hideChildren: function (comment) {
            this.currentExpanded = null
            let branchContainer = document.getElementById('branch-' + comment.id)

            branchContainer.classList.add('collapsed-branch')
        },
        handleDelete: function (comment) {
            let that = this
            commentApi.delete(comment.id).then(function (response) {
                that.$toast.success('Комментарий удалён')
                comment.deleted_at = response.data.deleted_at
                if (!comment.branch_id) {
                    return
                }

                for (let item of that.comments) {
                    if (item.id !== comment.branch_id) {
                        continue
                    }
                    for (let child of item.branch) {
                        if (child.id !== comment.id) {
                            continue
                        }
                        child.deleted_at = comment.deleted_at
                        break
                    }
                }
            })
        },
        canDelete: function (comment) {
            if (this.$auth.check()) {
                return this.$auth.user().id === comment.user_id;
            }

            return false;
        }
    },
    data: () => ({
        load: true,
        comments: {},
        canComment: false,
        disabled: true,
        currentComment: '',
        currentReplyCommentId: null,
        currentReplyCommentText: '',
        replyDisabled: true,
        nextPage: '',
        currentExpanded: null
    }),
    mounted() {
        this.canComment = this.$auth.check()
        let that = this

        commentApi.allByEntity({
            entity_id: this.entity_id,
            entity_table: this.entity_table
        }).then(function (response) {
            that.comments = response.data.data
            that.load = false
            that.nextPage = response.data.links.next
        })
    }
}
</script>

<style scoped>
    .select-area {
        background-color: #fffbdb;
        transition: background-color .4s;
    }

    .unselect-area {
        background-color: #fff;
        transition: background-color .4s;
    }

    .collapsed-branch .card {
        display: none;
    }

    .collapsed-branch .card:nth-child(1), .collapsed-branch .card:nth-child(2), .collapsed-branch .card:nth-child(3){
        display: flex;
    }
</style>
