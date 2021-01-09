<template>
    <div class="discussion-single-wrapper">
        <div class="top">
            <div class="discussion-type">{{discussion.type}}</div>
            <div class="restriction" v-if="computedRestriction.length">
                {{computedRestriction}}
            </div>
        </div>
        <div class="bottom">
            <div class="edit"
                @click="clickedEditIcon"
                v-if="computedUserParticipant"
            >
                <font-awesome-icon
                    :icon="['fa','chevron-down']"
                ></font-awesome-icon>
            </div>
            <div class="options" v-if="showOptions">
                <optional-actions
                    :show="showOptions"
                    :hasSave="!computedOwner"
                    :isSaved="isSaved"
                    :hasExtra="computedOwner && discussion.type === 'PRIVATE'"
                    extraText="requests"
                    :hasEdit="computedOwner"
                    :hasAttachment="computedUserParticipant"
                    :hasDelete="computedOwner"
                    @clickedOption="clickedOption"
                >
                    <template slot="extraicon">
                        <font-awesome-icon :icon="['fa','info-circle']"></font-awesome-icon>
                    </template>
                </optional-actions>
            </div>
            <div class="alert" 
                :class="{success:alertSuccess,danger:alertDanger}"
                v-if="alertMessage.length && !showDiscussionEdit && !showSmallModal"
            >
                {{alertMessage}}
            </div>
            <div class="loading" v-if="loading && !showDiscussionEdit">
                <pulse-loader 
                    :loading="loading" 
                    :size="'10px'"
                ></pulse-loader>
            </div>
            <div class="post-attachment" 
                v-if="computedAttachments"
                @click.self="showAttach = false"
            >
                <post-attachment
                    :show="showAttach"
                    :isAttached="isAttached"
                    :attachmentsNumber="attachments"
                    :attachments="myAttachments"
                    @itemClicked="attachmentClicked"
                    @clickedUnattach="clickedUnattach"
                ></post-attachment>
            </div>
            <div class="first" v-if="messageSectionState !=='max'">
                <div class="creator-info">
                    <div class="started">started by</div>
                    <profile-picture
                        class="profile-picture"
                    >
                        <template slot="image">
                            <img :src="discussion.profile_url" >
                        </template>
                    </profile-picture>
                    <div class="name">{{computedOwnerName}}</div>
                    <div class="buttons">
                        <post-button
                            buttonText="invite"
                            v-if="computedOwner"
                            @click="clickedPostButton"
                        ></post-button>
                        <post-button
                            buttonText="join"
                            v-if="computedJoin"
                            @click="clickedPostButton"
                        ></post-button>
                        <post-button
                            buttonText="leave"
                            v-if="!computedOwner && computedUserParticipant && !computedBanned"
                            @click="clickedPostButton"
                        ></post-button>
                        <div class="message" v-if="computedPendingParticipant">
                            your request is pending
                        </div>
                    </div>
                </div>
                <div class="discussion-info">
                    <div class="title">{{computedTitle}}</div>
                </div>
            </div>
            <div class="second" v-if="messageSectionState !=='max'">
                <div class="attachments-section">
                    <attachment-badge 
                        v-for="(attachment,index) in postAttachments"
                        :key="index"
                        :hasClose="false"
                        :attachment="attachment.data"
                        :type="attachment.type"
                    ></attachment-badge>
                </div>
                <div class="resources-section">
                    <template v-if="computedResources.length">
                        <div class="resource"
                            v-for="(resource, index) in computedResources"
                            :key="index"
                            @dblclick="clickedDiscussionMedia(resource)"
                        >
                            <img :src="resource.url" 
                                v-if="resource.type === 'image'">
                            <video :src="resource.url" controls 
                                v-if="resource.type === 'video'"></video>
                            <audio :src="resource.url" controls
                                v-if="resource.type === 'audio'"></audio>
                        </div>
                    </template>
                    <div class="no-resources" v-else>
                        no discussion resources
                    </div>
                </div>
            </div>
            <div class="third">
                <div class="admin-section" v-if="computedOwner && discussion.restricted">
                    <div class="admin-button"
                        @click="clickedAdminButton('all')"
                        :class="{active:adminButtonText === 'all'}"
                    >all</div>
                    <div class="admin-button"
                        @click="clickedAdminButton('accepted')"
                        :class="{active:adminButtonText === 'accepted'}"
                    >accepted</div>
                    <div class="admin-button"
                        @click="clickedAdminButton('rejected')"
                        :class="{active:adminButtonText === 'rejected'}"
                    >rejected</div>
                    <div class="admin-button"
                        @click="clickedAdminButton('pending')"
                        :class="{active:adminButtonText === 'pending'}"
                    >pending</div>
                </div>
                <div class="preamble" 
                    v-if="computedPreamble.length"
                    @click.self="clickedDiscussionInfo('view')"
                >
                    {{computedPreamble}}
                    <div class="toggle" 
                        @click="clickedMessageSectionToggle"
                        v-if="messages.length > 2"
                    >
                        <font-awesome-icon :icon="['fa','chevron-down']"
                            v-if="messageSectionState === 'max'"
                        ></font-awesome-icon>
                        <font-awesome-icon :icon="['fa','chevron-up']"
                            v-if="messageSectionState === 'min'"
                        ></font-awesome-icon>
                    </div>
                </div>
                <div class="discussion-section"
                    :class="{'discussion-section-max':messageSectionState === 'max'}"
                >
                    <div class="main-area" ref="mainarea">
                        <div class="unseen-messages"
                            v-if="unseenMessagesNumber && showUnseenMessages"
                            @click="clickedUnseenMessages"
                            @scroll="scrollingMainArea"
                        >
                            0
                        </div>
                        <div class="no-discussions" 
                            v-if="!messages.length && !messagesGetting && !messageSending"
                        >
                            no discussions yet
                        </div>
                        <discussion-badge
                            v-for="message in messages"
                            :key="message.id"
                            :message="message"
                            @clickedOption="clickedDiscussionOption"
                            @clickedAction="clickedDiscussionAction"
                            :admin="computedAdmin"
                            :adminText="adminButtonText"
                        ></discussion-badge>
                        <div class="show-discussions"
                            @click="getMessages"
                            v-if="computedShowDiscussions"
                        >
                            show discussions
                        </div>
                        <infinite-loader
                            @infinite="infiniteHandler"
                            v-if="showInfiniteLoader"
                        ></infinite-loader>
                        <fade-up>
                            <template slot="transition" v-if="messageSending">
                                <div class="loading">
                                    <pulse-loader 
                                        :loading="messageSending" 
                                        :size="'10px'"
                                    ></pulse-loader>
                                </div>
                            </template>
                        </fade-up>
                    </div>
                    <div class="text-area" v-if="computedUserParticipant">
                        <discussion-textarea
                            v-model="discussionText"
                            @sendMessage="sendDiscussionMessage"
                            @fileChange="discussionFileChange"
                            :blocked="computedBlocked"
                        ></discussion-textarea>
                    </div>
                </div>
            </div>
            <div class="forth">
                <div class="main">
                    <div class="reaction" 
                        :class="{unsetPosition: computedUnset}"
                    >
                        <post-button 
                            :titleText="flagTitle"
                            v-if="computedFlags && !computedOwner"
                            @click="clickedFlag"
                            :postButtonClass="flagRed"
                        >
                            <template slot="icon">
                                <font-awesome-icon
                                    :icon="['fa','flag']"
                                ></font-awesome-icon>
                            </template>
                        </post-button>
                        <div class="reason">
                            <flag-reason
                                :show="showFlagReason"
                                :hasBackground="true"
                                @continueFlagProcess="continueFlagProcess"
                                @reasonGiven="reasonGiven"
                                @cancelFlagProcess="cancelFlagProcess"
                            ></flag-reason>
                        </div>
                        <div class="like">
                            <number-of>
                                {{likes === 1 ? `${likes} like` : `${likes} likes`}}
                            </number-of>
                            <div class="others" 
                                :class="{unsetPosition: computedUnset}"
                            >
                                <div class="like-post"
                                    @click="clickedLike"
                                    v-if="computedLikes"
                                    :class="{liked:isLiked}"
                                    :title="likeTitle"
                                >
                                    <font-awesome-icon
                                        :icon="['fa','thumbs-up']"
                                    ></font-awesome-icon>
                                </div>
                                <div class="profiles"
                                    v-if="showProfiles"
                                >
                                    <span>
                                        {{showProfilesText}}
                                    </span>
                                    <div :key="key" v-for="(profile,key) in computedProfiles">
                                        <profile-bar
                                            :name="profile.name"
                                            :type="profile.params.account"
                                            :smallType="true"
                                            :routeParams="profile.params"
                                            :navigate="false"
                                            @clickedProfile="clickedProfile"
                                        ></profile-bar>
                                    </div>
                                </div>
                                <div class="comment"
                                    title="add a comment"
                                    @click="clickedAddComment"
                                    v-if="!showAddComment"
                                    :class="{success:commentSuccess,fail:commentFail}"
                                >
                                    <font-awesome-icon
                                        :icon="['fa','comment']"
                                    ></font-awesome-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="add-comment">
                        <add-comment
                            what="discussion"
                            :id="computedId"
                            :onPostModal="discussionFull"
                            :showAddComment="showAddComment"
                            @hideAddComment="showAddComment = false"
                            @postAddComplete="postAddComplete"
                            @postModalCommentCreated="postModalCommentCreated"
                        ></add-comment>
                    </div>
                    <div class="comment-section"
                        @dblclick.self="clickedShowPostComments">
                        <template v-if="!discussionFull && computedComments">
                            <comment-single
                                :key="comment.id" v-for="comment in computedComments"
                                :comment="comment"
                                :simple="true"
                                @askLoginRegister="askLoginRegister"
                                @clickedMedia="clickedMedia"
                                @clickedShowPostComments="clickedShowPostComments"
                            ></comment-single>
                        </template>
                    </div>
                </div>
            </div>
        </div>

       <!--  media modal -->
        <just-fade>
            <template slot="transition" v-if="showMediaModal">
                <media-modal
                    :show="showMediaModal"
                    :justUrl="true"
                    :url="mediaModalUrl.url"
                    :urlType="mediaModalUrl.type"
                    @mainModalDisappear="closeMediaModal"
                ></media-modal>
            </template>
        </just-fade>
       <!--  info section -->
        <just-fade>
            <template slot="transition" v-if="showDiscussionInfo">
                <div class="discusssion-info-section">
                    <div class="close" @click="clickedDiscussionInfo('view')">
                        <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
                    </div>
                    <div class="pencil" 
                        @click="clickedDiscussionInfo('edit')"
                        v-if="(computedOwner || computedAdmin) && !showDiscussionEdit"
                    >
                        <font-awesome-icon :icon="['fa','pencil-alt']"></font-awesome-icon>
                    </div>
                    <div class="title">
                        {{showDiscussionEdit ? 'edit discussion information' : 'discussion information'}}
                    </div>
                    <div class="body" v-if="!showDiscussionEdit">
                        <div class="section">Admin</div>
                        <div class="owner-section">
                            <div class="name">{{computedOwnerName}}</div>
                            <div class="account">{{computedOwnerType}}</div>
                        </div>
                        <div class="section" v-if="showParticipantsButton !== 'hide'">Information</div>
                        <div class="info-section" v-if="showParticipantsButton !== 'hide'">
                            <div class="info-item">
                                <div class="label">title</div>
                                <div class="item">{{computedTitle}}</div>
                            </div>
                            <div class="info-item">
                                <div class="label">type</div>
                                <div class="item">{{computedType}}</div>
                            </div>
                            <div class="info-item">
                                <div class="label">restriction</div>
                                <div class="item">
                                    {{discussion.restricted ? 'restricted mode' : 'unrestricted mode'}}
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="label">participants</div>
                                <div class="item">{{computedParticipantsInfo}}</div>
                            </div>
                            <div class="info-item">
                                <div class="label">note</div>
                                <div class="item">{{computedParticipationNote}}</div>
                            </div>
                            <div class="info-item">
                                <div class="label">attachments</div>
                                <div class="item">
                                    <attachment-badge></attachment-badge>
                                </div>
                            </div>
                        </div>
                        <div class="section">Participation</div>
                        <div class="show-participants"
                            @click="clickedShowParticipants"
                        >{{showParticipantsButton}}</div>
                        <div class="participants-section"
                            v-if="showParticipants"
                            infinite-wrapper>
                            <other-user-account
                                v-for="participant in participants"
                                :key="participant.id"
                                :account="participant"
                                :loading="otherUserAccountLoading"
                                :admin="computedAdmin"
                                :owner="computedOwner"
                                :participant="true"
                                :participating="computedUserParticipant"
                                @clickedParticipantAction="clickedParticipantAction"
                            ></other-user-account>
                            <div class="loading" v-if="participantsLoading">
                                <pulse-loader :loading="participantsLoading" size="10px"></pulse-loader>
                            </div>
                        </div>
                        <infinite-loader
                            @infinite="infiniteHandlerParticipants"
                            v-if="participantsNextPage && participantsNextPage > 1"
                            force-use-infinite-wrapper
                        ></infinite-loader>
                    </div>
                    <just-fade>
                        <template slot="transition" v-if="showDiscussionEdit">
                            <div class="edit-section">
                                <div class="alert" 
                                    :class="{success:alertSuccess,danger:alertDanger}"
                                    v-if="alertMessage.length && showDiscussionEdit"
                                >
                                    {{alertMessage}}
                                </div>
                                <div class="loading" v-if="loading && showDiscussionEdit">
                                    <pulse-loader 
                                        :loading="loading" 
                                        :size="'10px'"
                                    ></pulse-loader>
                                </div>
                                <div class="section">Discussion Info</div>
                                <div class="form-edit">
                                    <text-input
                                        placeholder="discussion title"  
                                        :bottomBorder="true"
                                        :error="errorTitle"
                                        v-model="title"></text-input>
                                </div>
                                <div class="form-edit">
                                    <text-textarea type="text" 
                                        placeholder="discussion preamble (an introduction to the discussion)"
                                        :bottomBorder="true"
                                        v-model="preamble"></text-textarea>
                                </div>
                                <div class="form-edit">
                                    <div class="main-section">
                                        <div class="label">type:</div>
                                        <grey-button
                                            class="grey-button"
                                            @clickedAction="clickedEditActionButton('public')"
                                            :active="toLowercase(type) === 'public'"
                                            text="public"
                                        ></grey-button>
                                        <grey-button
                                            class="grey-button"
                                            @clickedAction="clickedEditActionButton('private')"
                                            :active="toLowercase(type) === 'private'"
                                            text="private"
                                        ></grey-button>
                                    </div>
                                </div>
                                <div class="form-edit">
                                    <div class="main-section">
                                        <div class="label">restricted:</div>
                                        <grey-button
                                            class="grey-button"
                                            @clickedAction="clickedEditActionButton('yes')"
                                            :active="restricted"
                                            text="yes"
                                        ></grey-button>
                                        <grey-button
                                            class="grey-button"
                                            @clickedAction="clickedEditActionButton('no')"
                                            :active="!restricted"
                                            text="no"
                                        ></grey-button>
                                    </div>
                                </div>
                                <div class="form-edit">
                                    <div class="main-section">
                                        <div class="label">allowed:</div>
                                        <grey-button
                                            class="grey-button"
                                            @clickedAction="clickedEditActionButton('all')"
                                            :active="toLowercase(allowed) === 'all'"
                                            text="main"
                                        ></grey-button>
                                        <grey-button
                                            class="grey-button"
                                            @clickedAction="clickedEditActionButton('learners')"
                                            :active="toLowercase(allowed) === 'learners'"
                                            text="learners"
                                        ></grey-button>
                                        <grey-button
                                            class="grey-button"
                                            @clickedAction="clickedEditActionButton('parents')"
                                            :active="toLowercase(allowed) === 'parents'"
                                            text="parents"
                                        ></grey-button>
                                        <grey-button
                                            class="grey-button"
                                            @clickedAction="clickedEditActionButton('facilitators')"
                                            :active="toLowercase(allowed) === 'facilitators'"
                                            text="facilitators"
                                        ></grey-button>
                                        <grey-button
                                            class="grey-button"
                                            @clickedAction="clickedEditActionButton('professionals')"
                                            :active="toLowercase(allowed) === 'professionals'"
                                            text="professionals"
                                        ></grey-button>
                                        <grey-button
                                            class="grey-button"
                                            @clickedAction="clickedEditActionButton('schools')"
                                            :active="toLowercase(allowed) === 'schools'"
                                            text="schools"
                                        ></grey-button>
                                    </div>
                                </div>
                                <div class="section">Discussion Resources</div>
                                <div class="info">you can up upload up to three files</div>
                                <div class="files" v-if="computedEditFilesLength < 3">
                                    <div class="file"
                                        @click="clickedFileType('video')"
                                        :class="{active: fileType === 'video'}"
                                    >video</div>
                                    <div class="file"
                                        @click="clickedFileType('audio')"
                                        :class="{active: fileType === 'audio'}"
                                    >audio</div>
                                    <div class="file"
                                        @click="clickedFileType('picture')"
                                        :class="{active: fileType === 'picture'}"
                                    >picture</div>
                                </div>
                                <div class="actions" v-if="computedEditFilesLength < 3">
                                    <div class="action"
                                        @click="clickedEditAction('upload')"
                                        v-if="fileType.length"
                                        :title="`upload ${fileType}`"
                                    >
                                        <font-awesome-icon :icon="['fa','upload']"></font-awesome-icon>
                                    </div>
                                    <div class="action"
                                        v-if="fileType === 'video'" 
                                        @click="clickedEditAction('video')"
                                        title="record a video"
                                    >
                                        <font-awesome-icon :icon="['fa','video']"></font-awesome-icon>
                                    </div>
                                    <div class="action"
                                        v-if="fileType === 'picture'" 
                                        @click="clickedEditAction('camera')"
                                        title="snap a picture"
                                    >
                                        <font-awesome-icon :icon="['fa','camera']"></font-awesome-icon>
                                    </div>
                                    <div class="action"
                                        v-if="fileType === 'audio'" 
                                        @click="clickedEditAction('microphone')"
                                        title="record an audio"
                                    >
                                        <font-awesome-icon :icon="['fa','microphone']"></font-awesome-icon>
                                    </div>
                                </div>
                                <div class="media-section resources" v-if="computedEditFilesLength">
                                    <div class="media-item"
                                        v-for="(mediaItem,index) in files"
                                        :key="index"
                                    >
                                        <div class="item-type" @click="clickedEditFile(mediaItem,'resource')">
                                            <font-awesome-icon :icon="['fa','image']"></font-awesome-icon>
                                        </div>
                                        <div class="item-info" @click="clickedEditFile(mediaItem,'resource')">
                                            {{mediaItem.name ? mediaItem.name : shortenUrl(mediaItem.url)}}
                                        </div>
                                        <div class="item-clear"
                                            @click="clickedEditBan(mediaItem,'resource')"
                                            :title="`remove ${getFileType(mediaItem.type)}`"
                                        >
                                            <font-awesome-icon :icon="['fa','ban']"></font-awesome-icon>
                                        </div>
                                    </div>
                                </div>
                                <div class="media-section uploads" v-if="uploadFiles.length">
                                    <div class="media-item"
                                        v-for="(mediaItem,index) in uploadFiles"
                                        :key="index"
                                    >
                                        <div class="item-type" @click="clickedEditFile(mediaItem,'upload')">
                                            <font-awesome-icon :icon="['fa','image']"></font-awesome-icon>
                                        </div>
                                        <div class="item-info" @click="clickedEditFile(mediaItem,'upload')">
                                            {{mediaItem.name ? mediaItem.name : shortenUrl(mediaItem.url)}}
                                        </div>
                                        <div class="item-clear"
                                            @click="clickedEditBan(mediaItem,'upload')"
                                            :title="`remove ${getFileType(mediaItem.type)}`"
                                        >
                                            <font-awesome-icon :icon="['fa','ban']"></font-awesome-icon>
                                        </div>
                                    </div>
                                </div>
                                <fade-up>
                                    <template slot="transition" v-if="showFilePreview">
                                        <file-preview
                                            class="file-preview"
                                            :file="activeFile"
                                            :middle="true"
                                            @removeFile="removeFile"
                                        ></file-preview>
                                    </template>
                                </fade-up>
                                <input type="file" class="d-none" 
                                    @change="editFileChange" 
                                    ref="inputfile"
                                    :accept="fileAccept"
                                    multiple
                                >
                        
                                <div class="buttons">
                                    <post-button 
                                        :buttonText="'edit'" 
                                        buttonStyle='success'
                                        @click="clickedEdit"
                                    ></post-button>
                                    <post-button 
                                        :buttonText="'cancel'" 
                                        buttonStyle='danger'
                                        @click="clickedEdit"
                                    ></post-button>
                                </div>
                            </div>
                        </template>
                    </just-fade>
                </div>
            </template>
        </just-fade>
       <!--  request section -->
        <just-fade>
            <template slot="transition" v-if="showDiscussionRequest">
                <div class="discusssion-request-section">
                    <div class="close" @click="clickedCloseDiscussionRequest">
                        <font-awesome-icon :icon="['fa','times']"></font-awesome-icon>
                    </div>
                    <div class="alert"
                        :class="{success:alertSuccess,danger:alertDanger}"
                        v-if="alertMessage.length"
                    >
                        {{alertMessage}}
                    </div>
                    <div class="title">invite accounts to join this discussion</div>
                    <div class="body">
                        <search-input
                            class="search-section"
                            searchPlaceholder="search whom to invite?"
                            @search="receivedParticipantsSearchText"
                        ></search-input>
                        <div class="search-types">
                            <grey-button
                                class="grey-button"
                                @clickedAction="clickedSearchType('profiles')"
                                :active="searchType === 'profiles'"
                                text="all"
                                v-if="discussion.allowed === 'ALL'"
                            ></grey-button>
                            <grey-button
                                class="grey-button"
                                @clickedAction="clickedSearchType('learners')"
                                :active="searchType === 'learners'"
                                text="learners"
                                v-if="discussion.allowed === 'ALL' || discussion.allowed === 'LEARNERS'"
                            ></grey-button>
                            <grey-button
                                class="grey-button"
                                @clickedAction="clickedSearchType('parents')"
                                :active="searchType === 'parents'"
                                text="parents"
                                v-if="discussion.allowed === 'ALL' || discussion.allowed === 'PARENTS'"
                            ></grey-button>
                            <grey-button
                                class="grey-button"
                                @clickedAction="clickedSearchType('facilitators')"
                                :active="searchType === 'facilitators'"
                                text="facilitators"
                                v-if="discussion.allowed === 'ALL' || discussion.allowed === 'FACILITATORS'"
                            ></grey-button>
                            <grey-button
                                class="grey-button"
                                @clickedAction="clickedSearchType('professionals')"
                                :active="searchType === 'professionals'"
                                text="professionals"
                                v-if="discussion.allowed === 'ALL' || discussion.allowed === 'PROFESSIONALS'"
                            ></grey-button>
                            <grey-button
                                class="grey-button"
                                @clickedAction="clickedSearchType('schools')"
                                :active="searchType === 'schools'"
                                text="schools"
                                v-if="discussion.allowed === 'ALL' || discussion.allowed === 'SCHOOLS'"
                            ></grey-button>
                        </div>
                        <div class="accounts-section">
                            <div class="no-participants" v-if="!searchLoading && noSearchParticipants">
                                no search results
                            </div>
                            <participant-badge
                                v-for="(participant,index) in searchParticipants"
                                :key="index"
                                :account="participant"
                                class="participant-badge"
                                :invite="true"
                                :inviting="searchInvitationLoading"
                                @clickedAction="clickedParticpantAction"
                            ></participant-badge>
                            <div class="loading" v-if="searchLoading">
                                <pulse-loader :loading="searchLoading" size="10px"></pulse-loader>
                            </div>
                            <div class="show-more"
                                v-if="!searchLoading && showMorerSearchParticipants"
                                @click="search"
                            >
                                show more
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </just-fade>
        
        <!-- media capture -->
        <media-capture
            v-if="showMediaCapture"
            :show="showMediaCapture"
            :type="mediaCaptureType"
            @closeMediaCapture="closeMediaCapture"
            @sendFile="receiveMediaCapture"
        ></media-capture>

        <!-- small modal for alerts -->
        <fade-up>
            <template slot="transition" v-if="showSmallModal">
                <small-modal
                    :title="smallModalTitle"
                    :show="showSmallModal"
                    :message="alertMessage"
                    :success="alertSuccess"
                    :danger="alertDanger"
                    :loading="loading"
                    :alerting="smallModalAlerting"
                    @disappear="clearSmallModal"
                >
                    <template slot="actions">
                        <post-button
                            buttonText="ok"
                            @click="clickedSmallModalButton"
                            v-if="smallModalInfo"
                        ></post-button>
                        <post-button
                            buttonText="yes"
                            @click="clickedSmallModalButton"
                            buttonStyle='danger'
                            v-if="smallModalDelete"
                        ></post-button>
                        <post-button
                            buttonText="no"
                            @click="clickedSmallModalButton"
                            buttonStyle='success'
                            v-if="smallModalDelete"
                        ></post-button>
                    </template>
                </small-modal>
            </template>
        </fade-up>
    </div>
</template>

<script>
import MediaModal from './MediaModal';
import FadeUp from './transitions/FadeUp'
import DiscussionBadge from './DiscussionBadge';
import AttachmentBadge from './AttachmentBadge';
import FlagReason from './FlagReason';
import ProfileBar from './profile/ProfileBar';
import AddComment from './AddComment';
import PostButton from './PostButton';
import NumberOf from './NumberOf';
import DiscussionTextarea from './DiscussionTextarea';
import PostAttachment from './PostAttachment';
import OptionalActions from './OptionalActions';
import MediaCapture from './MediaCapture';
import TextTextarea from './TextTextarea';
import TextInput from './TextInput';
import GreyButton from './GreyButton';
import OtherUserAccount from './chat/OtherUserAccount';
import InfiniteLoader from 'vue-infinite-loading';
import ProfilePicture from './profile/ProfilePicture';
import ParticipantBadge from './discussion/ParticipantBadge';
import SearchInput from './SearchInput';
import PulseLoader from 'vue-spinner/src/PulseLoader';
import { mapActions, mapGetters } from 'vuex';
import { strings } from '../services/helpers';
    export default {
        components: {
            PulseLoader,
            SearchInput,
            ParticipantBadge,
            ProfilePicture,
            InfiniteLoader,
            FadeUp,
            OtherUserAccount,
            GreyButton,
            MediaCapture,
            OptionalActions,
            PostAttachment,
            DiscussionTextarea,
            NumberOf,
            TextInput,
            TextTextarea,
            PostButton,
            AddComment,
            ProfileBar,
            FlagReason,
            AttachmentBadge,
            DiscussionBadge,
            MediaModal,
        },
        props: {
            discussion: {
                type: Object,
                default(){
                    return {}
                }
            },
            discussionFull: {
                type: Boolean,
                default: false,
            },
            disabled: { //when being viewed by admin as an activity
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                showMediaModal: false,
                mediaModalUrl: {},
                showProfilesAction: '',
                showProfiles: false,
                showFlagReason: false,
                showDiscussionInfo: false,
                showDiscussionRequest: false,
                showOptions: false,
                showParticipants: false,
                loading: false,
                participantsLoading: false,
                otherUserAccountLoading: false,
                showParticipantsButton: 'participants',
                requestType: '',
                messageSectionState: 'min',
                adminButtonText: 'all',
                participants: [],
                fileType: '',
                participantsNextPage: 1,
                //likes
                likeTitle: '',
                isLiked: false,
                myLike: null,
                likes: 0,
                //comment
                showAddComment: false,
                commentSuccess: false,
                commentFail: false,
                //flags
                showFlagReason: false,//it also pushes reaction section down to show flag reason
                flagReason: '',
                isFlagged: false,
                myFlag: null,
                flagTitle: '',
                flagRed: '',
                //profiles
                showProfilesAction: '',
                showProfilesText: '',
                //save
                isSaved: false,
                mySave: null,
                saves: 0,
                //attach
                isAttached: false, //means i have attached and it goes for likes, etc
                myAttachments: null,
                attachments: 0,
                postAttachments: [],
                attachable: null,
                showAttach: false,
                selectedAttachment: null,
                //messages
                messageNextPage: 1,
                messages: [],
                discussionFiles: [],
                discussionText: '',
                messageSending: false,
                messagesGetting: false,
                showInfiniteLoader: false,
                showUnseenMessages: false,
                unseenMessagesNumber: 0,
                //alert
                alertMessage: '',
                alertDanger: false,
                alertSuccess: false,
                //editing
                showDiscussionEdit: false,
                showMediaCapture: false,
                mediaCaptureType: '',
                fileType: '',
                fileAccept: '',
                title: '',
                type: '',
                preamble: '',
                allowed: '',
                restricted: false,
                files: [],
                uploadFiles: [],
                deletedFiles: [],
                errorTitle: false,
                fileNumberError: false,
                showFilePreview: false,
                activeFile: null,
                //small modal
                smallModalDelete: false,
                smallModalInfo: false,
                smallModalAlerting: false,
                showSmallModal: false,
                smallModalTitle: '',
                smallModalData: null,
                //search
                searchText: '',
                searchType: 'profiles',
                searchParticipants: [],
                searchNextPage: 1,
                searchLoading: false,
                searchInvitationLoading: false,
                noSearchParticipants: true,
                showMorerSearchParticipants: false,
            }
        },
        watch: {
            discussion: {
                immediate: true,
                handler(newValue, oldValue){
                    if (newValue.messages) {
                        this.messages = newValue.messages
                    }
                    if (oldValue && newValue.participants.length >
                        oldValue.participants.length) {
                        this.alertSuccess = true
                        this.alertMessage = 'a new participant just joined'
                        this.clearAlert()
                    }
                },
                deep: true
            },
            searchParticipants(newValue){
                if (newValue.length) {
                    this.noSearchParticipants = false
                } else {
                    this.noSearchParticipants = true
                    this.showMorerSearchParticipants = false
                }
            },
            showDiscussionRequest(newValue){
                if (!newValue) {
                   this.searchParticipants = []
                } else {
                    if (this.discusssion.allowed === 'ALL') {
                        this.searchType = 'profiles'
                    } else if (this.discusssion.allowed === 'LEARNERS') {
                        this.searchType = 'learners'
                    } else if (this.discusssion.allowed === 'PARENTS') {
                        this.searchType = 'parents'
                    } else if (this.discusssion.allowed === 'FACILITATORS') {
                        this.searchType = 'facilitators'
                    } else if (this.discusssion.allowed === 'PROFESSIONALS') {
                        this.searchType = 'professionals'
                    } else if (this.discusssion.allowed === 'SCHOOLS') {
                        this.searchType = 'schools'
                    }
                }
            },
            showUnseenMessages(newValue){
                if (newValue) {
                    setTimeout(() => {
                        this.showUnseenMessages = false
                    }, 5000);
                }
            },
            searchNextPage(newValue){
                if (newValue === 1) {
                    this.noSearchParticipants = false
                } else if (newValue > 1) {
                    this.showMorerSearchParticipants = true
                }
            },
            searchType(newValue){
                this.searhInvitableParticipants()
            },
            searchText(newValue){
                if (newValue.length) {
                    this.searhInvitableParticipants()
                } else {
                    this.searchParticipants = []
                }
            },
            showProfiles(newValue){
                if (newValue) {
                    setTimeout(() => {
                        this.showProfiles = false
                    }, 4000);
                }
            },
            showDiscussionEdit(newValue){
                if (newValue && !this.title.length) {
                    this.title = this.discussion.title
                    this.preamble = this.discussion.preamble
                    this.type = this.discussion.type
                    this.restricted = this.discussion.restricted
                    this.allowed = this.discussion.allowed
                    this.files = this.computedResources
                    this.deletedFiles = []
                    this.uploadFiles = []
                } else if (!newValue) {
                    this.title = ''
                }
            },
            isLiked(newValue){
                if (newValue) {
                    this.likeTitle = 'unlike this discussion'
                } else {
                    this.likeTitle = 'like this discussion'
                }
            },
            likes(newValue){
                if (!newValue) {
                    this.myLike = null
                    this.isLiked = false
                }
            },
            attachments(newValue){
                if (!newValue) {
                    this.myAttachments = null
                    this.isAttached = false
                }
            },
            isFlagged(newValue){
                if (newValue) {
                    this.flagTitle = 'unflag this discussion'
                    this.flagRed = 'red'
                } else {
                    this.flagTitle = 'flag this discussion'
                    this.flagRed = ''
                }
            },
            saves(newValue){
                if (!newValue) {
                    this.mySave = null
                    this.isSaved = false
                }
            },
            title(newValue, oldValue) {
                if (newValue.length && !oldValue.length) {
                    this.errorTitle = false
                }
            },
            messageNextPage(newValue){
                if (newValue && newValue > 2) {
                    this.showInfiniteLoader = true
                } else {
                    this.showInfiniteLoader = false
                }
            },
            showParticipants(newValue){
                if (newValue) {
                    this.getDiscussionParticipants()
                }
            },
        },
        mounted(){
            this.listen()
        },
        computed: {
            ...mapGetters(['getUser','getProfiles']),
            computedRestriction() {
                return this.discussion.restricted ? 'restricted participation' : ''
            },
            computedRestricted() {
                return !this.computedParticipant ? false : 
                    this.computedParticipant.state === 'RESTRICTED' || 
                    this.computedParticipant.state === 'BANNED'
            },
            computedBanned() {
                return !this.computedParticipant ? false : 
                    this.computedParticipant.state === 'BANNED'
            },
            computedEditFilesLength(){
                return this.type.length ? 
                    this.files.length + 
                    this.uploadFiles.length : 0
            },
            computedResources(){
                let resources = []

                if (this.discussion.images) {
                    
                    this.discussion.images.forEach(resource=>{
                        resources.push({
                            url: resource.url,
                            id: resource.id,
                            type: 'image'
                        })
                    })
                }
                if (this.discussion.videos) {
                    
                    this.discussion.videos.forEach(resource=>{
                        resources.push({
                            url: resource.url,
                            id: resource.id,
                            type: 'video'
                        })
                    })
                }
                if (this.discussion.audios) {
                    
                    this.discussion.audios.forEach(resource=>{
                        resources.push({
                            url: resource.url,
                            id: resource.id,
                            type: 'audio'
                        })
                    })
                }
                return resources
            },
            computedUnset(){
                return this.showProfilesAction === 'save' || 
                    this.showProfilesAction === 'attach' || 
                    this.showProfilesAction === 'join'
            },
            computedAdmin(){
                return this.computedOwner || (this.computedUserParticipant && 
                    this.discussion.participants.findIndex(participant=>{
                        return participant.userId === this.getUser.id && 
                            participant.state === 'ADMIN'
                    }) > -1)
            },
            computedShowDiscussions(){
                return this.computedMessagesCount > 2 &&
                    this.messages.length <= 2 && !this.messagesGetting
            },
            computedCanJoin(){
                if (this.computedAllowed !== 'ALL') {
                    return this.computedProfiles.filter(profile=>{
                        return profile.params.account == this.computedProfiles
                    }).map(profile=>{
                        return {
                            account: profile.params.account,
                            accountId: profile.params.accountId
                        }
                    })
                }
                return {}
            },
            computedMessagesCount(){
                return this.discussion.messages_count
            },
            computedProfiles(){
                return this.getProfiles ? this.getProfiles : []
            },
            computedLikes(){
                if (this.getUser && this.discussion) {
                    let likes = this.discussion.likes
                    this.likes = this.discussion.likes.length
                    let index = null
                    index = likes.findIndex(like=>{
                            return like.user_id === this.getUser.id
                        })
                    if (index > -1) {
                        this.myLike = likes[index]
                        this.isLiked = true
                    }
                }
                return true
            },
            computedSaves(){
                if (this.getUser && this.discussion) {
                    let saves = this.discussion.saves
                    this.saves = this.discussion.saves.length
                    let index = null
                    index = saves.findIndex(save=>{
                            return save.user_id === this.getUser.id
                        })
                    if (index > -1) {
                        this.mySave = saves[index]
                        this.isSaved = true
                    }
                }
                return true
            },
            computedFlags(){ //check flagging
                if (this.getUser && this.discussion) {
                    let flags = this.discussion.flags
                    let index = null
                    index = flags.findIndex(flag=>{
                            return flag.user_id === this.getUser.id
                        })
                    if (index > -1) {
                        this.myFlag = flags[index]
                        this.isFlagged = true
                    }
                }
                return true
            },
            computedComments(){
                return this.discussion && this.discussion.comments.length > 0 ?
                    _.take(this.discussion.comments,2) : null
            },
            computedId(){
                return this.discussion.id
            },
            computedBlocked(){
                return this.getUser && this.computedRestricted ? true : false
            },
            computedOwnerName(){
                return this.discussion.raisedby
            },
            computedOwnerType(){
                return strings.getAccount(this.discussion.raisedby_type)
            },
            computedAllowed(){
                return this.discussion.allowed
            },
            computedType(){
                return this.discussion.type.toLowerCase()
            },
            computedTitle(){
                return this.discussion.title
            },
            computedPreamble(){
                return this.discussion.preamble
            },
            computedAttachments(){ //check attachment .....
                if (this.getUser && this.discussion) {
                    let attachments = []
                    this.attachments = this.discussion.attachments.length
                    attachments = this.discussion.attachments.filter(attachment=>{
                        return attachment.user_id === this.getUser.id
                    }).map(attachment=>{
                        return {
                            id: attachment.id,
                            with_type: strings.getAccount(attachment.attachedwith_type),
                            with_id: attachment.attachedwith_id,
                            with: attachment.name,
                        }
                    })

                    this.postAttachments = this.discussion.attachments.map(attach=>{
                        return {
                            data: {name: attach.name},
                            type: strings.getAccount(attach.attachedwith_type)
                        }
                    })
                    if (attachments.length) {
                        this.isAttached = true
                        this.myAttachments = attachments
                    }
                }
                return this.showAttach
            }, //add ability to add other admins feature
            computedParticipationNote(){
                return this.computedOwner ? 'you are the owner and admin of this discussion' :
                    this.computedUserParticipant ? 'you are a participant in this discussion' :
                    this.computedAllowed === 'ALL' && this.computedProfiles ? 'you can join this discussion' :
                    this.computedCanJoin.account ? 'you can join this discussion' :
                    this.computedProfiles && !this.computedCanJoin.account ? 
                        'you cannot join this discussion because you do not have an allowable account': ''
            },
            computedOwner(){
                return this.getUser && this.discussion.raisedby_user_id === this.getUser.id
            },
            computedParticipantsInfo(){
                return this.computedParticipantsNumber === 1 ? 
                    `${this.computedParticipantsNumber} participant` :
                    `${this.computedParticipantsNumber} participants`
            },
            computedParticipantsNumber(){
                return this.discussion.participants.length + 1
            },
            computedParticipants(){
                return this.discussion.participants
            },
            computedParticipant(){
                if (!this.getUser) {
                    return null
                }
                let index = this.discussion.participants.findIndex(participant=>{
                    return participant.userId === this.getUser.id
                })
                if (index > -1) {
                    return this.discussion.participants[index]
                }
                return null
            },
            computedPendingParticipant(){
                return this.getUser && 
                    this.discussion.pendingJoinParticipants.findIndex(pending=>{
                        return pending.userId === this.getUser.id
                    }) > -1
            },
            computedUserParticipant(){
                return this.computedOwner || (this.computedParticipant && 
                    this.computedParticipant.id) ? true : false
            },
            computedJoin(){
                return !this.computedPendingParticipant && !this.computedOwner && 
                    !this.computedUserParticipant
            },
        },
        methods: {
            ...mapActions(['profile/getDiscussionMessages','profile/sendDiscussionMessage',
                'profile/joinDiscussion','profile/updateDiscussion',
                'profile/deleteDiscussion','profile/deleteDiscussionMessage',
                'profile/newDiscussionParticipant','profile/removeDiscussionParticipant',
                'home/newDiscussionParticipant','home/removeDiscussionParticipant',
                'profile/updateDiscussionParticipant','home/updateDiscussionParticipant',
                'profile/newDiscussionPendingParticipant',
                'home/newDiscussionPendingParticipant','profile/discusionContributionResponse',
                'profile/removeDiscussionPendingParticipant','profile/discussionSearch',
                'home/removeDiscussionPendingParticipant','profile/inviteParticipant',
                'profile/createLike','profile/deleteLike','profile/deleteSave',
                'profile/createSave','profile/createFlag','profile/deleteFlag',
                'profile/createAttachment','profile/deleteAttachment',
                'profile/getDiscussionParticipants','profile/updateParticpantState',
                'profile/deleteDiscussionParticipant']),
            listen(){
                Echo.channel(`youredu.discussion.${this.discussion.id}`)
                    .listen('.newDiscussionMessage', data=>{
                        console.log('data :>> ', data);
                        if (this.discussion.restricted) {
                            if (this.computedOwner && this.adminButtonText === 'pending') {
                                this.messages.unshift(data.message)
                            } else {
                                this.alertSuccess = true
                                this.alertMessage = 'a new contribution has been sent'
                                this.clearAlert()
                            }
                        } else  this.messages.unshift(data.message)
                    })
                    .listen('.newDiscussionMessageResponse', data=>{
                        console.log('data :>> ', data);
                        if (this.discussion.restricted) {
                            if (this.computedAdmin) {
                                if ((this.adminButtonText === 'all' ||
                                    this.adminButtonText === 'accepted') &&
                                    data.message.state === 'ACCEPTED') {
                                    this.alertSuccess = true
                                    this.messages.unshift(data.message)
                                } else if (this.adminButtonText === 'rejected' &&
                                    data.message.state === 'REJECTED'){
                                    this.messages.unshift(data.message)
                                    this.alertDanger = true
                                }
                                this.alertMessage = `a new contribution has been ${data.message.state.toLowerCase()}`
                                this.clearAlert()
                                return
                            } else if (data.message.state === "ACCEPT") {

                                this.alertSuccess = true
                                this.alertMessage = 'a new contribution has been sent'
                                this.clearAlert()
                                this.messages.unshift(data.message)
                            }
                        }
                    })
                    .listen('.deleteDiscussionMessage', data=>{
                        console.log('data :>> ', data);
                        this.spliceMessage(data.messageId)
                    })
                    .listen('.newDiscussionParticipant', data=>{
                        console.log('data :>> ', data);
                        if (this.$route.name === 'home') {
                            this['home/newDiscussionParticipant'](data)
                        } else if (this.$route.name === 'profile') {
                            this['profile/newDiscussionParticipant'](data)
                        }
                        
                        this.alertSuccess = true
                        this.alertMessage = 'a new participant just joined this discussion'
                        this.clearAlert()
                    })
                    .listen('.updatedDiscussionParticipant', data=>{
                        console.log('data :>> ', data);
                        if (this.$route.name === 'home') {
                            this['home/updateDiscussionParticipant'](data)
                        } else if (this.$route.name === 'profile') {
                            this['profile/updateDiscussionParticipant'](data)
                        }
                        if (this.getUser && data.discussionParticipant.userId === this.getUser.id) {
                            if (data.discussionParticipant.state === 'RESTRICTED' ||
                                data.discussionParticipant.state === 'BANNED') {
                                this.showDiscussionInfo = false
                                this.showDiscussionEdit = false
                                this.alertDanger = true
                                this.alertMessage = `you have been ${data.discussionParticipant.state.toLowerCase()}`
                                this.clearAlert()
                            }
                        }                        
                    })
                    .listen('.removeDiscussionParticipant', data=>{
                        console.log('data :>> ', data);
                        if (this.$route.name === 'home') {
                            this['home/removeDiscussionParticipant'](data)
                        } else if (this.$route.name === 'profile') {
                            this['profile/removeDiscussionParticipant'](data)
                        }
                        if (this.getUser && data.userId === this.getUser.id) {
                            this.showDiscussionInfo = false
                            this.showDiscussionEdit = false
                            this.alertDanger = true
                            this.alertMessage = `you have been removed`
                            this.clearAlert()
                        }    
                    })
                    .listen('.newDiscussionPendingParticipant', data=>{
                        console.log('data :>> ', data);
                        if (this.computedAdmin) {
                            this.alertSuccess = true
                            this.alertMessage = 'new join request received'
                            this.clearAlert()
                        }
                        if (this.$route.name === 'home') {
                            this['home/newDiscussionPendingParticipant'](data)
                        } else if (this.$route.name === 'profile') {
                            this['profile/newDiscussionPendingParticipant'](data)
                        }
                    })
                    .listen('.removeDiscussionPendingParticipant', data=>{
                        console.log('data :>> ', data);
                        if (this.$route.name === 'home') {
                            this['home/removeDiscussionPendingParticipant'](data)
                        } else if (this.$route.name === 'profile') {
                            this['profile/removeDiscussionPendingParticipant'](data)
                        }
                    })
            },
            clickedSearchType(data){
                this.searchType = data
            },
            clickedMessageSectionToggle(){
                this.messageSectionState = this.messageSectionState === 'max' ?
                    'min' : 'max'
            },
            clickedParticpantAction(data){
                if (data.action === 'invite') {
                    this.inviteParticipant(data)
                }
            },
            async inviteParticipant(invitationData){
                this.searchInvitationLoading = true
                let response,
                    data = {
                        account: invitationData.account.account,
                        accountId: invitationData.account.accountId,
                        discussionId: this.discussion.id
                    }

                response = await this['profile/inviteParticipant'](data)

                this.searchInvitationLoading = false
                if (response.status) {
                    this.alertSuccess = true
                    this.alertMessage = 'invitation sent'
                    this.removeSearchParticipant(invitationData.account.userId)
                } else {
                    console.log('response :>> ', response);
                    this.alertDanger = true
                    this.alertMessage = 'invitation failed'
                }
                this.clearAlert()
            },
            removeSearchParticipant(userId){
                this.searchParticipants = this.searchParticipants.filter(participant=>{
                    return participant.userId !== userId
                })
            },
            receivedParticipantsSearchText(data){
                this.searchText = data
            },
            searhInvitableParticipants: _.debounce(function() {
                this.searchNextPage = 1
                this.search()
            }, 400),
            async search(){
                if (this.searchNextPage === null || !this.searchText.length) {
                    return 
                }
                this.searchLoading = true
                let response = null,
                    params = {
                        discussionId: this.discussion.id,
                        search: this.searchText,
                        searchType: this.searchType
                    },
                    data = {}

                data.nextPage = this.searchNextPage
                data.params = params

                response = await this['profile/discussionSearch'](data)

                if (response.status) {
                    if (this.searchType !== 'messages') {
                        this.searchParticipants.push(...response.data)
                    } else {

                    }
                    if (this.searchNextPage === 1 && !this.searchParticipants.length) {
                        this.noSearchParticipants = true
                    }
                    if (response.next) {
                        this.searchNextPage += 1
                    } else {
                        this.showMorerSearchParticipants = false
                        this.searchNextPage = null
                    }
                } else {
                    console.log('response :>> ', response);
                }
                this.searchLoading = false
            },
            async clickedDiscussionAction(messageData){
                let response,
                    data = {
                        userId: messageData.message.userId,
                        messageId: messageData.message.id,
                        action: messageData.action
                    }
                
                response = await this['profile/discusionContributionResponse'](data)

                if (response.status) {
                    if ((this.adminButtonText === 'all' && data.action === 'accepted' &&
                        messageData.message.state === 'ACCEPTED') ||
                        (this.adminButtonText === messageData.message.state.toLowerCase())) {
                        this.spliceMessage(messageData.message.id)
                    }
                    if ((this.adminButtonText === 'all' && data.action === 'accepted' &&
                        response.discussionMessage.state === 'ACCEPTED') ||
                        (this.adminButtonText === response.discussionMessage.state.toLowerCase())) {
                        this.unshiftMessage(response.discussionMessage,messageData.adminText)
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            unshiftMessage(message,adminText){
                this.messages.unshift(message)
            },
            spliceMessage(messageId){
                console.log('messageId :>> ', messageId);
                let index = this.messages.findIndex(message=>{
                    return message.id == messageId
                })
                console.log('index :>> ', index);
                if (index > -1) {
                    this.messages.splice(index,1)
                }
            },
            clickedDiscussionOption(data){
                this.deleteMessage(data)
            },
            async deleteMessage(data){
                let response

                response = await this['profile/deleteDiscussionMessage']({
                    messageId: data.message.id,
                    discussionId: this.discussion.id,
                    action: data.action === 'delete' ? data.action : 'self'
                })

                if (response.status) {
                    if (data.action === 'delete') {
                        this.spliceMessage(data.message.id)
                    } else {
                        this.replaceMessage(response.discussionMessage)
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            replaceMessage(discussionMessage){
                let index = this.messages.findIndex(message=>{
                    return message.id === discussionMessage.id
                })
                if (index > -1) {
                    this.messages.splice(index,1,discussionMessage)
                }
            },
            clickedFileType(data){
                if (data === 'picture') {
                    this.fileAccept = 'image/apng,image/bmp,image/gif,image/x-icon,image/jpeg,image/png,image/svg+xml,image/webp'
                } else if (data === 'video') {
                    this.fileAccept = 'video/webm,video/mp4,video/ogg'
                } else if (data === 'audio') {
                    this.fileAccept = 'audio/mpeg,audio/ogg,audio/wav'
                }
                this.fileType = data
            },
            clickedSmallModalButton(data){
                if (data === 'yes') {
                    if (this.showProfilesAction === 'flag') {
                        this.flag(this.smallModalData)
                    } else if (this.showProfilesAction === 'delete') {
                        this.deleteDiscussion()
                    } else if (this.showProfilesAction === 'participant') {
                        this.deleteDiscussionParticipant(this.smallModalData)
                    }
                } else if (data === 'no') {
                    this.otherUserAccountLoading = false //incase this is for leaving or removing participants
                    this.clearSmallModal()
                } else if (data === 'ok') {
                    
                }
            },
            clickedEditBan(data, type){
                if (type === 'upload') {
                    let index = this.uploadFiles.findIndex(file=>{
                        return file.name === data.name && file.size === data.size
                    })
                    if (index > -1) {
                        this.uploadFiles.splice(index,1)
                    }
                } else if (type === 'resource') {
                    let index = this.files.findIndex(file=>{
                        return file.type === data.type && file.id === data.id
                    })
                    if (index > -1) {
                        this.files.splice(index,1)
                        this.deletedFiles.push(data)
                    }
                }
                this.showFilePreview = false
            },
            removeFile(){
                this.fileType = ''
                this.showFilePreview = false
                this.clickedEditBan(this.activeFile,'upload')
            },
            editFileChange(){
                if (this.$refs.inputfile.files.length + this.computedEditFilesLength > 3) {
                    this.$refs.inputfile.value = ''
                    return
                }
                this.activeFile = this.$refs.inputfile.files[0]
                for (let i = 0; i < this.$refs.inputfile.files.length; i++) {
                    this.uploadFiles.push(this.$refs.inputfile.files[i])
                }
                this.showFilePreview = true
            },
            clickedEdit(data){
                if (data === 'edit') {
                    if (!this.title.length) {
                        this.errorTitle = true
                        this.alertMessage = 'a title is needed'
                        this.clearAlert()
                    }
                    this.updateDiscussion()
                } else if (data === 'cancel') {
                    this.showDiscussionInfo = false
                    this.showDiscussionEdit = false
                }
            },
            async deleteDiscussion(){
                this.loading = true
                    data = {where: this.$route.name}

                let response = await this['profile/deleteDiscussion']({
                    discussionId: this.discussion.id,data})

                this.loading = false
                if (response.status) {
                    this.alertSuccess = true
                    this.alertMessage = 'deletion was successful'
                } else {
                    console.log('response :>> ', response);
                    this.alertDanger = true
                    this.alertMessage = response.response.data.message
                }
                this.smallModalAlerting = true
                this.clearAlert()
            },
            async updateDiscussion(){
                let response,
                    formData = new FormData,
                    data = {}

                this.loading = true

                if (this.computedOwner) {
                    formData.append('account', strings.getAccount(this.discussion.raisedby_type))
                    formData.append('accountId', this.discussion.raisedby_id)
                }
                formData.append('title', this.title)
                formData.append('type', this.type)
                formData.append('restricted', JSON.stringify(this.restricted))
                formData.append('allowed', this.allowed)
                formData.append('preamble', this.preamble)
                this.files.forEach(file=>{
                    formData.append('files[]', file)
                })
                formData.append('deletedFiles', JSON.stringify(this.deletedFiles))
                data.discussionId = this.discussion.id
                data.formData = formData
                data.where = this.$route.name

                response = await this['profile/updateDiscussion'](data)

                this.loading = false
                if (response.status) {
                    this.alertSuccess = true
                    this.alertMessage = 'update was successful'
                } else {
                    console.log('response :>> ', response);
                    this.alertDanger = true
                    this.alertMessage = 'update was unsuccessful'
                }
                this.clearAlert()
                setTimeout(() => {
                    this.showDiscussionEdit = false
                    this.showDiscussionInfo = false
                }, 3000);
            },
            shortenUrl(data){
                return strings.content(data,20)
            },
            getFileType(data){
                if (data.includes('image')) {
                    return 'image'
                } else if (data.includes('video')) {
                    return 'video'
                } else if (data.includes('audio')) {
                    return 'audio'
                }
            },
            clickedEditAction(data){
                if (data === 'video') {
                    this.mediaCaptureType = 'video'
                    this.showMediaCapture = true
                } else if (data === 'camera') {
                    this.mediaCaptureType = 'image'
                    this.showMediaCapture = true
                } else if (data === 'microphone') {
                    this.mediaCaptureType = 'audio'
                    this.showMediaCapture = true
                } else if (data === 'upload') {
                    this.$refs.inputfile.value = ''
                    this.$refs.inputfile.click()
                }
            },
            toLowercase(data){
                return data.toLowerCase()
            },
            clickedFileType(data){
                if (data === 'picture') {
                    this.fileAccept = 'image/apng,image/bmp,image/gif,image/x-icon,image/jpeg,image/png,image/svg+xml,image/webp'
                } else if (data === 'video') {
                    this.fileAccept = 'video/webm,video/mp4,video/ogg'
                } else if (data === 'audio') {
                    this.fileAccept = 'audio/mpeg,audio/ogg,audio/wav'
                }
                this.fileType = data
            },
            closeMediaCapture(){
                this.showMediaCapture = false
            },
            receiveMediaCapture(file){
                let time = new Date().getTime(),
                    fileName
                if (file.type.includes('image')) {
                    fileName = `my_picture${time}.png`
                    this.activeFile = this.imageFile = new File([file],fileName,{
                        type: 'image/png',
                        lastModified: new Date()
                    })
                } else if (file.type.includes('video')) {
                    fileName = `my_video${time}.webm`
                    this.activeFile = this.videoFile = new File([file],fileName,{
                        type: 'video/webm',
                        lastModified: new Date()
                    })
                } else if (file.type.includes('audio')) {
                    fileName = `my_audio${time}.mp3`
                    this.activeFile = this.audioFile = new File([file],fileName,{
                        type: 'audio/mp3',
                        lastModified: new Date()
                    })
                }
                this.uploadFiles.push(this.activeFile)
                this.showFilePreview = true
            },
            clickedEditFile(data,type){
                if (type === 'resource') {
                    this.mediaModalUrl = data
                    this.showMediaModal = true
                } else if (type === 'upload') {
                    this.showFilePreview = this.activeFile === data ?
                        false : true
                    this.activeFile = data
                }
            },
            scrollingMainArea(){
                console.log('mainarea :>> ', this.$refs.mainarea.scrollTop);
            },
            clickedUnseenMessages(){
                this.showUnseenMessages = false,
                this.unseenMessagesNumber = 0
            },
            clickedEditIcon(){
                if (this.disabled) {
                    return
                }
                if(this.computedBanned) return
                this.showOptions = !this.showOptions
                if (this.showOptions) {
                    setTimeout(() => {
                        this.showOptions = false
                    }, 4000);
                }
                this.showAttach = false
            },
            clickedShowParticipants(){
                this.showParticipants = !this.showParticipants
                if (this.showParticipants) {
                    this.showParticipantsButton = 'hide'
                } else {
                    this.showParticipantsButton = 'participants'
                    this.participantsNextPage = 1
                    this.participants = []
                }
            },
            clickedEditActionButton(data){
                if (data === 'all') {
                    this.allowed = data
                } else if (data === 'learners') {
                    this.allowed = data
                } else if (data === 'parents') {
                    this.allowed = data
                } else if (data === 'facilitators') {
                    this.allowed = data
                } else if (data === 'professionals') {
                    this.allowed = data
                } else if (data === 'schools') {
                    this.allowed = data
                } else if (data === 'private') {
                    this.type = data
                } else if (data === 'public') {
                    this.type = data
                } else if (data === 'yes') {
                    this.restricted = true
                } else if (data === 'no') {
                    this.restricted = false
                }
            },
            clickedOption(data){
                this.showOptions = false
                if (data === 'edit') {
                    this.showDiscussionInfo = true
                    this.showDiscussionEdit = true
                } else if (data === 'save') {
                    if (this.isSaved) {
                        this.save(null)
                        return
                    }
                    this.showProfilesText = 'save as'
                    this.showProfilesAction = 'save'
                    this.profilesAppear()
                } else if (data === 'attach') {
                    this.showAttach = true
                } else if (data === 'delete') {
                    this.smallModalTitle = 'are you sure you want to delete this discussion?'
                    this.showProfilesAction = 'delete'
                    this.smallModalDelete = true
                    this.showSmallModal = true
                } else if (data === 'requests') {
                    this.requestType = data
                    this.showDiscussionRequest = true
                }
            }, 
            clickedUnattach(attachment){
                this.showAttach = false
                this.selectedAttachment = attachment
                this.attach(null)
            },
            attachmentClicked(data){
                this.showAttach = false
                this.attachable = data
                this.showProfilesAction = 'attach'
                this.showProfilesText = 'attach as'
                this.profilesAppear()
            },
            async attach(who){

                this.loading = true
                let data = {},
                    response = null,
                    state = ''

                data.where = this.$route.name
                data.item = 'discussion'
                data.itemId = this.discussion.id
                if (who) {
                    state = 'attachment'
                    data.attachable = this.attachable.item
                    data.attachableId = this.attachable.itemId
                    data.account = who.account
                    data.accountId = who.accountId
                    data.note = this.attachable.note
                    response = await this['profile/createAttachment'](data)
                } else {
                    state = 'unattachment'
                    data.attachmentId = this.selectedAttachment.id
                    response = await this['profile/deleteAttachment'](data)
                }
                
                this.loading = false
                if (response.status) {
                    if (who) {
                        this.attachments += 1
                    } else {
                        this.attachments -= 1
                    }
                    this.alertSuccess = true
                    this.alertMessage = `${state} successful`
                } else {
                    this.alertDanger = true
                    this.alertMessage = `${state} unsuccessful`
                }
                setTimeout(() => {
                    this.clearAlert()
                }, 3000);
            },
            clearSmallModal(){
                this.showSmallModal = false,
                this.smallModalInfo = false
                this.smallModalDelete = false
                this.smallModalAlerting = false
                this.smallModalTitle = ''
            },
            clickedAdminButton(data){
                if (this.adminButtonText !== data) {
                    this.messageNextPage = 1
                    this.getMessages(data)
                }
                this.adminButtonText = data
            },
            closeMediaModal() {
                this.showMediaModal = false
            },
            clickedPostButton(data){
                if (data === 'invite') {
                    this.requestType = data
                    this.showDiscussionRequest = true
                } else if (data === 'join') {
                    if (this.computedAllowed === 'ALL') {
                        this.showProfilesAction = 'join'
                        this.showProfiles = true
                    } else if (this.computedCanJoin.account) {
                        this.joinDiscussion(this.computedCanJoin)
                    } else if (!this.getUser) {
                        this.askLoginRegister('discussion')
                    }
                } else if (data === 'leave') {
                    this.showProfilesAction = 'participant'
                    this.clickedLeaveRemoveParticipant({action: data})
                }
            },
            clickedLeaveRemoveParticipant(data){
                this.otherUserAccountLoading = true
                this.smallModalInfo= false
                this.smallModalDelete = true
                this.smallModalData = data
                if (data.action === 'leave') {                    
                    this.smallModalTitle = 'are you sure you want to leave this discussion?'
                } else {
                    this.smallModalTitle = 'are you sure you want to remove this participant from the discussion?'
                }
                this.showSmallModal = true
                setTimeout(() => {
                    this.clearSmallModal()
                }, 4000);
            },
            async joinDiscussion(account){
                this.loading = true
                let response,
                    discussionId = this.discussion.id,
                    data = {
                        account: account.account,
                        accountId: account.accountId,
                        type: this.discussion.type
                    }

                response = await this['profile/joinDiscussion']({data, discussionId})

                this.loading = false
                if (response.status) {
                    this.alertSuccess = true
                    if (this.discussion.type === "PRIVATE") {
                        this.alertMessage = 'your join request has been sent'
                    } else {
                        this.alertMessage = 'you just joined this discussion'
                    }
                } else {
                    console.log('response :>> ', response);
                    this.alertDanger = true
                    this.alertMessage = 'you could not join'
                }
                this.clearAlert()
            },
            clearAlert(){
                setTimeout(() => {
                    if (this.showSmallModal) {
                        this.clearSmallModal()
                    }
                    this.alertSuccess = false
                    this.alertDanger = false
                    this.alertMessage = ''
                }, 4000);
            },
            askLoginRegister(data){
                this.$emit('askLoginRegister',data)
            },
            clickedCloseDiscussionRequest(){
                this.showDiscussionRequest = false
            },
            clickedDiscussionInfo(data){
                if(this.computedBanned) return
                if (data === 'view') {
                    if (this.showDiscussionInfo) {
                        this.showDiscussionEdit = false
                    }
                    this.showDiscussionInfo = !this.showDiscussionInfo
                } else if (data === 'edit') {
                    this.showDiscussionEdit = true
                }
            },
            clickedDiscussionMedia(resource){
                this.mediaModalUrl = resource
                this.showMediaModal = true
            },
            async sendDiscussionMessage(){
                this.messageSending = true
                let response,
                    formData = new FormData,
                    discussionId = this.discussion.id

                if (this.computedOwner) {
                    formData.append('account', this.computedOwnerType)
                    formData.append('accountId', this.discussion.raisedby_id)
                } else {
                    formData.append('userId', this.getUser.id)
                }
                formData.append('message', this.discussionText)
                if (this.discussionFiles.length) {
                    this.discussionFiles.forEach(file=>{
                        formData.append('file[]', file)
                    })
                }
                if (this.discussion.restricted) {
                    if (this.computedAdmin) {
                        formData.append('state','ACCEPTED')
                    } else formData.append('state','PENDING')
                } else {
                    formData.append('state','ACCEPTED')
                }

                response = await this['profile/sendDiscussionMessage']({
                    discussionId, formData
                })

                this.messageSending = false
                if (response.status) {
                    if (this.discussion.restricted) {
                        this.alertSuccess = true
                        this.alertMessage = 'your message is pending'
                    } else this.messages.unshift(response.discussionMessage)
                    if (this.$refs.mainarea.scrollTop !== 0) {
                        this.showUnseenMessages = true
                        this.unseenMessagesNumber += 1
                    }
                } else {
                    console.log('response :>> ', response);
                    this.alertDanger = true
                    this.alertMessage = 'message sending failed'
                }
                this.clearAlert()
            },
            discussionFileChange(data){
                this.discussionText = data.caption.length ? data.caption.length : this.discussionText
                this.discussionFiles = data.files
            },
            async save(who){
                this.showProfiles = false
                this.loading = true
                let data = {
                    item: 'discussion',
                    itemId: this.discussion.id,
                    owner: 'discussion',
                    ownerId: this.discussion.id,
                },
                    response = null,
                    state = ''

                data.where = this.$route.name
                if (who) {
                    data.account = who.account
                    data.accountId = who.accountId
                    state = 'saving'
                    response = await this['profile/createSave'](data)
                } else {
                    data.saveId = this.mySave.id
                    state = 'unsaving'
                    response = await this['profile/deleteSave'](data)
                }

                this.loading = false
                if (response.status) {
                    if (who) {
                        this.saves += 1
                    } else {
                        this.saves -= 1
                    }
                    this.isSaved = !this.isSaved
                    this.alertSuccess = true
                    this.alertMessage = `${state} successful`
                } else {
                    this.alertDanger = true
                    this.alertMessage = `${state} unsuccessful`
                }
                setTimeout(() => {
                    this.alertSuccess = false
                    this.alertDanger = false
                    this.alertMessage = ''
                }, 3000);
            },
            async getMessages(data = null){
                let response,
                    discussionId = this.discussion.id,
                    nextPage = this.messageNextPage,
                    type = data ? data : this.adminButtonText

                this.messagesGetting = true
                response = await this['profile/getDiscussionMessages']({
                    discussionId, nextPage, type
                })

                if (response.status) {
                    this.messages = response.data
                    if (response.next) {
                        this.messageNextPage += 1
                        this.showInfiniteLoader = true
                    } else {
                        this.messageNextPage = null
                    }
                } else {
                    console.log('response :>> ', response);
                }
                this.messagesGetting = false
            },
            async infiniteHandler($state){
                if (this.messageNextPage === 1) {
                    return
                }
                if (this.messageNextPage === null) {
                    $state.complete()
                    return
                }
                let response,
                    discussionId = this.discussion.id,
                    nextPage = this.messageNextPage,
                    type = this.adminButtonText

                this.messageSending = true
                response = await this['profile/getDiscussionMessages']({discussionId,nextPage})

                this.messageSending = false
                if (response.status) {
                    this.messages.push(...response.data)
                    if (response.next) {
                        this.messageNextPage += 1
                        $state.loaded()
                    } else {
                        this.messageNextPage = null
                        $state.complete()
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            async getDiscussionParticipants(){
                this.participantsLoading = true

                let response,
                    data = {}

                data.nextPage = this.participantsNextPage
                data.discussionId = this.discussion.id
                
                response = await this['profile/getDiscussionParticipants'](data)

                this.participantsLoading = false
                if (response.status) {
                    this.participants = response.data
                    if (response.next) {
                        this.participantsNextPage +=1
                    } else {
                        this.participantsNextPage = null
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            async infiniteHandlerParticipants($state){
                if (this.participantsNextPage === 1) {
                    return
                }
                if (this.participantsNextPage === null) {
                    $state.complete()
                    return
                }
                this.participantsLoading = true
                data.nextPage = this.participantsNextPage
                data.discussionId = this.discussion.id
                
                response = await this['profile/getDiscussionParticipants'](data)

                this.participantsLoading = false
                if (response.status) {
                    this.participants = response.data
                    if (response.next) {
                        this.participantsNextPage +=1
                        $state.loaded()
                    } else {
                        this.participantsNextPage = null
                        $state.complete()
                    }
                } else {
                    console.log('response :>> ', response);
                }
            },
            clickedParticipantAction(data){
                if (data.action === 'remove' || data.action === 'leave') {
                    this.showProfilesAction = 'participant'
                    this.clickedLeaveRemoveParticipant(data)
                } else {
                    this.otherUserAccountLoading = true
                    this.updateParticpantState(data)
                }
            },
            async deleteDiscussionParticipant(deleteData){
                let response,
                    data = {
                        discussionId: this.discussion.id,
                        action: deleteData.action
                    }
                this.loading = true
                if (deleteData.action === 'leave') {
                    data.participantId = this.computedParticipant.id
                    data.userId = this.computedParticipant.userId
                } else {
                    data.participantId = deleteData.account.participantId
                    data.userId = deleteData.account.userId
                }

                response = await this['profile/deleteDiscussionParticipant'](data)

                this.loading = false
                this.otherUserAccountLoading = false
                if (response.status) {
                    this.alertSuccess = true
                    if (deleteData.action === 'leave') {
                        this.alertMessage = 'left successfully'
                    } else {
                        this.alertMessage = 'removed participant successfully'
                    }
                    this.removeParticipant(data.participantId)
                } else {
                    console.log('response :>> ', response);
                    this.alertDanger = true
                    this.alertMessage = response.response.data.message
                }
                this.smallModalAlerting = true
                this.clearAlert()
            },
            async updateParticpantState(updateData){
                let response,
                    data = {
                        discussionId: this.discussion.id,
                        participantId: updateData.account.participantId,
                        userId: updateData.account.userId,
                        action: updateData.action,
                    }

                response = await this['profile/updateParticpantState'](data)

                this.otherUserAccountLoading = false
                if (response.status) {
                    this.replaceParticipant(response.discussionParticipant)
                } else {
                    console.log('response :>> ', response);
                }
            },
            removeParticipant(participantId){
                let index = this.participants.findIndex(p=>{
                    return p.participantId === participantId
                })
                if (index > -1) {
                    this.participants.splice(index,1)
                }
            },
            replaceParticipant(participant){
                let index = this.participants.findIndex(p=>{
                    return p.participantId === participant.participantId
                })
                if (index > -1) {
                    this.participants.splice(index,1,participant)
                }
            },
            postModalCommentCreated(data){
                if (this.postMediaFull) {
                    this.$emit('postModalCommentCreated',data)
                }
            },
            clickedShowPostComments(){
                this.$emit('clickedShowPostComments',{post: this.post,type:'post'})
            },
            postAddComplete(data){
                if (data === 'successful') {
                    this.showAddComment = false
                    this.commentSuccess = true
                    setTimeout(() => {
                        this.commentSuccess = false
                    }, 2000);
                } else {
                    this.commentFail = true
                    setTimeout(() => {
                        this.commentFail = false
                    }, 2000);
                }
            },
            clickedAddComment(){
                if (this.disabled) {
                    return
                }
                if(this.computedBanned) return
                if (!this.getUser) {
                    this.$emit('askLoginRegister','discussionsingle')
                } else if (!this.getProfiles || !this.getProfiles.length) {
                    this.smallModalInfo= true
                    this.smallModalDelete = false
                    this.smallModalTitle = 'you must have an account (eg. learner, parent, etc) before you can comment.'
                    this.showSmallModal = true
                    setTimeout(() => {
                        this.clearSmallModal()
                    }, 4000);
                } else {
                    this.showAddComment = true
                }
            },
            clickedProfile(data){
                this.showProfiles = false
                if (this.showProfilesAction === 'join') {
                    this.joinDiscussion(data)
                } else if (this.showProfilesAction === 'like') {
                    this.like(data)
                } else if (this.showProfilesAction === 'save') {
                    this.save(data)
                } else if (this.showProfilesAction === 'flag') {
                    this.smallModalTitle = 'are you sure you want to flag this?'
                    this.smallModalDelete = true
                    this.showSmallModal = true
                    this.smallModalData = data
                    // setTimeout(() => {
                    //     this.clearSmallModal()
                    // }, 4000);
                } else if (this.showProfilesAction === 'attach'){
                    this.attach(data)
                }
            },
            clickedMedia(data){

            },
            reasonGiven(data){
                this.showFlagReason = false
                this.flagReason = data
                this.profilesAppear()
            },
            continueFlagProcess(){
                this.flagReason = null
                this.showFlagReason = false
                this.profilesAppear()
            },
            cancelFlagProcess(){
                this.flagReason = ''
                this.showFlagReason = false
            },
            clickedFlag(){
                if (this.disabled) {
                    return
                }
                if(this.computedBanned) return
                if (this.isFlagged) {
                    this.flag(null)
                    return
                }
                this.showProfilesText = 'flag as'
                this.showProfilesAction = 'flag'
                if (!this.getUser) {
                    this.$emit('askLoginRegister','discussionsingle')
                } else if (!this.getProfiles.length) { // to ensure that people with no profiles dont like/comment/flag
                    this.smallModalInfo= true
                    this.smallModalDelete = false
                    this.smallModalTitle = 'you must have an account (eg. learner, parent, etc) before you can flag.'
                    this.showSmallModal = true
                    setTimeout(() => {
                        this.clearSmallModal()
                    }, 4000);
                } else {
                    this.showFlagReason = true
                }
            },
            async flag(who){
                this.loading = true
                let data = {}
                data.where = this.$route.name
                data.discussion = true
                data.itemId = this.discussion.id
                let response = null
                if (who) {
                    data.account = who.account
                    data.accountId = who.accountId
                    data.item = 'discussion'
                    data.reason = this.flagReason

                    response = await this['profile/createFlag'](data)
                } else {
                    data.flagId = this.myFlag.id

                    response = await this['profile/deleteFlag'](data)
                }

                this.loading =false
                if (response.status) {
                    this.alertSuccess = true
                    if (this.isFlagged) {
                        this.isFlagged = false
                        this.$emit('postUnflaggedSuccess', {
                            flag: response.flag,
                            answerId: this.discussion.id
                        })
                    } else {
                        this.alertModalMessage = 'successfully flagged'
                        this.$emit('postDeleteSuccess',{postId: data.itemId,type:'discussion'})
                    }
                    this.smallModalAlerting = true
                } else {
                    this.alertDanger = true
                    this.alertModalMessage = 'flagging successful'
                }
                this.flagReason = ''
                this.smallModalData = null
                setTimeout(() => {
                    this.clearSmallModal()
                }, 3000);
            },
            async clickedLike(data){
                if (this.disabled) {
                    return
                }
                if(this.computedBanned) return
                if (!this.getUser) {
                    this.$emit('askLoginRegister','discussionsingle')
                } else if (!this.getProfiles.length) {
                    this.smallModalInfo= true
                    this.smallModalDelete = false
                    this.smallModalTitle = 'you must have an account (eg. learner, parent, etc) before you can like.'
                    this.showSmallModal = true
                    setTimeout(() => {
                        this.clearSmallModal()
                    }, 4000);
                } else {
                    this.showProfilesText = 'like as'
                    this.showProfilesAction = 'like'
                    if (this.isLiked) {
                        this.likes -= 1
                        this.isLiked = false
                        
                        if (this.myLike && this.myLike.hasOwnProperty('id')) {
                            let newData = {
                                likeId: this.myLike.id,
                                item: 'discussion',
                                itemId: this.discussion.id,
                                owner: this.discussion.raisedby_type,
                                ownerId: this.discussion.raisedby_id,
                            }

                            newData.where = this.$route.name
                            let response = await this['profile/deleteLike'](newData)
                            if (response === 'unsuccessful') {
                                this.isLiked = true
                                this.likes += 1
                            }
                        } else {
                            this.likes += 1
                            this.isLiked = true
                        }
                    } else {
                        this.profilesAppear()
                    }
                }
            },
            async like(who){
                this.showProfiles = false
                this.isLiked = true
                this.likes += 1
                let data = {
                    item: 'discussion',
                    itemId: this.discussion.id,
                    account: who.account,
                    accountId: who.accountId,
                    owner: this.discussion.raisedby_type,
                    ownerId: this.discussion.raisedby_id,
                }

                data.where = this.$route.name
                let response = await this['profile/createLike'](data)

                if (response === 'unsuccessful') {
                    this.isLiked = false
                    this.likes -= 1
                }
            },
            profilesAppear(){
                this.showProfiles = true
                setTimeout(() => {
                    this.showProfiles = false
                }, 4000);
            },
        },
    }
</script>

<style lang="scss" scoped>

@mixin info(){
    text-align: center;
    font-size: 12px;
    color: gray;
}

@mixin loading(){
    width: 100%;
    text-align: center;
    padding: 5px;
}

@mixin section(){
    width: 100%;
    color: gray;
    font-size: 12px;
    border-bottom: 1px solid gray;
    margin-top: 10px;
}

@mixin show-more(){
    padding: 5px;
    border-radius: 10px;
    margin: 5px auto;
    width: fit-content;
    background: white;
    font-size: 11px;
    box-shadow: 0 0 2px green;
    cursor: pointer;
    color: green;
    font-weight: 600;
}

@mixin close(){
    color: gray;
    position: absolute;
    right: 3px;
    top: 3px;
    padding: 5px;
    font-size: 14px;
    cursor: pointer;

    &:hover{
        color: red;
        transition: all 1s ease-in-out;
    }
}

@mixin button(){
    padding: 5px;
    width: fit-content;
    font-size: 14px;
    min-width: 35px;
    text-align: center;
    margin: 0 5px;
    border-radius: 10px;
    box-shadow: 0 0 2px grey;
    color: gray;
    cursor: pointer;
}

    .discussion-single-wrapper{
        position: relative;
        width: 100%;
        margin: 10px 0;

        .top{
            display: table;
            width: 100%;
            font-size: 11px;
            color: gray;

            .discussion-type{
                display: table-cell;
                width: 40%;
            }

            .restriction{
                width: 60%;
                display: table-cell;
                text-align: end;
                font-size: 10px;
                @include text-overflow();
            }
        }

        .bottom{
            border: 1px solid dimgrey;
            border-right: 2px solid dimgray;
            position: relative;

            .loading,
            .alert{
                @include loading();
            }

            .alert{
                font-size: 12px;
                color: white;
            }

            .success{
                background-color: green;
            }

            .danger{
                background-color: red;
            }

            .edit{
                font-size: 16px;
                margin-top: -5px;
                margin-right: 2px;
                cursor: pointer;
                text-align: end;
            }

            .options{ 
                z-index: 1;
                position: absolute;
                right: 0;
                top: 0;
            }

            .post-attachment{
                top: 0;
                height: 100%;
                width: 100%;
                position: absolute;
                z-index: 1;
            }

            .first{

                .creator-info{
                    position: relative;
                    width: 100%;
                    display: flex;
                    justify-content: flex-start;
                    align-items: center;
                    padding: 5px;

                    .started{
                        font-size: 10px;
                        margin-right: 5px;
                    }

                    .profile-picture{
                        width: 30px;
                        height: 30px;
                    }

                    .name{
                        font-size: 14px;
                    }

                    .buttons{
                        width: fit-content;
                        float: right;
                        margin-left: auto;

                        .message{
                            font-size: 11px;
                            color: gray;
                            text-align: end;
                        }
                    }
                }

                .discussion-info{
                    padding: 5px;
                    border-bottom: 1px solid gray;

                    .title{
                        text-transform: capitalize;
                        font-size: 14px;
                    }
                }     
            }

            .second{

                .attachments-section{
                    padding: 5px;
                    width: 100%;
                    display: flex;
                    justify-content: flex-end;
                    align-items: center;
                    overflow-x: auto;
                }

                .resources-section{
                    height: 120px;
                    width: 100%;
                    overflow: hidden;
                    display: inline-flex;
                    display: -webkit-inline-box;
                    display: -moz-inline-box;
                    border-bottom: 1px solid gray;
                    padding: 5px;
                    overflow-x: auto;

                    .no-resources{
                        width: 100%;
                        height: 100%;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                    }

                    .resource{
                        width: 150px;
                        height: 100%;
                        margin: 0 10px;

                        img,
                        video{
                            height: 100%;
                            width: 100%;
                            object-fit: fill;
                        }

                        audio{
                            max-width: 150px;
                        }
                    }
                }
            }

            .third{
                
                .admin-section{
                    padding: 5px;
                    display: inline-flex;
                    width: 100%;
                    justify-content: space-between;
                    border-bottom: 1px solid gray;

                    .admin-button{
                        @include button();
                    }

                    .active{
                        color: white;
                        background-color: gray;
                    }
                }

                .preamble{
                    font-size: 12px;
                    width: 100%;
                    padding: 5px;
                    color: gray;
                    background-color: mintcream;
                    cursor: pointer;
                    @include text-overflow();
                    position: relative;

                    .toggle{ 
                        font-size: 16px;
                        color: gray;
                        z-index: 1;
                        float: right;
                        margin-right: 5px;
                    }
                }

                .discussion-section{
                    border-bottom: 1px solid gray;

                    .main-area{
                        height: 200px;
                        border-bottom: 1px solid gray;
                        padding: 10px;
                        overflow-y: auto;
                        position: relative;

                        .no-discussions{
                            width: 100%;
                            height: 150px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                        }

                        .unseen-messages{
                            position: absolute;
                            right: 5px;
                            top: 5px;
                            width: fit-content;
                            border-radius: 50%;
                        }

                        .show-discussions{
                            @include show-more();
                        }

                        .loading{
                            position: absolute;
                            bottom: 0;
                            left: 0;
                            width: 100%;
                            text-align: center;
                        }
                    }

                    .text-area{
                        min-height: 75px;
                    }
                }

                .discussion-section-max{

                    .main-area{
                        height: 650px;
                    }
                }
            }

            .forth{
                padding: 5px;

                .main{
                    width: 100%;
                    padding-top: 5px;

                    .reaction{
                        display: flex;
                        justify-content: space-between;
                        align-items: center; 
                        position: relative;

                        .reason{
                            position: absolute;
                            top: 100%;
                            z-index: 3;
                        }

                        .like{
                            display: inline-flex;
                            align-items: center;
                            justify-content: space-between;
                            width: 50%;  
                            margin-left: auto;

                            .others{
                                display: inline-flex;
                                justify-content: flex-end;
                                align-items: center;
                                position: relative;

                                .like-post{
                                    margin-right: 10px;
                                    padding: 5px;
                                    font-size: 16px;
                                    cursor: pointer;
                                }

                                .liked{
                                    color: green;
                                }   

                                .profiles{
                                    position: absolute;
                                    width: 200px;
                                    right: 0;
                                    z-index: 1000;
                                    text-align: start;
                                    top: 15px;

                                    span{
                                        font-size: 12px;
                                        font-weight: 500;
                                    }
                                }

                                .comment{
                                    cursor: pointer;
                                    font-size: 16px;
                                }
                            }

                            .unsetPosition{
                                position: unset;
                            }
                        }
                    }

                    .unsetPosition{
                        position: unset;
                    }

                    .add-comment{
                        width: 75%;
                        position: relative;
                        margin: 10px 0 0 auto;
                    }

                    .comment-section{
                        width: 85%;
                        margin: 5px 0 0 auto;
                    }
                }
            }
        }

        .discusssion-info-section,
        .discusssion-request-section{
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: mintcream;
            border-radius: 10px;
            box-shadow: 0 0 2px grey;

            .close, 
            .pencil{
                color: gray;
                position: absolute;
                right: 10px;
                top: 10px;
                padding: 5px;
                font-size: 14px;
                cursor: pointer;

                &:hover{
                    color: red;
                    transition: all 1s ease-in-out;
                }
            }

            .pencil{
                right: 40px;
                top: 5px;

                &:hover{
                    color: green;
                }
            }
            
            .title{
                width: 100%;
                text-align: center;
                margin: 20px 0 0;
                color: gray;
                text-transform: capitalize;
            }

            .body{
                padding: 10px;
                height: 88%;
            }
        }

        .discusssion-info-section{

            .body{

                .section{
                    @include section();
                }

                .owner-section{
                    display: inline-flex;
                    width: 100%;
                    justify-content: space-between;
                    align-items: center;

                    .name{
                        font-size: 14px;
                        text-transform: capitalize;
                    }

                    .account{
                        font-size: 12px;
                        color: gray;
                    }
                }

                .info-section{

                    .info-item{
                        display: table;
                        width: 100%;
                        font-size: 14px;
                        padding: 5px;

                        .label{
                            display: table-cell;
                            width: 80px;
                            max-width: 30%;
                            color: gray;
                            padding-right: 5px;
                        }

                        .item{
                            font-weight: 500;
                            width: 100%;
                            font-variant: small-caps;
                        }
                    }
                }

                .show-participants{
                    @include button();
                    margin: 10px auto;
                }

                .participants-section{
                    height: 75%;
                    overflow-y: auto;
                    padding: 10px;
                    width: 100%;

                    .loading{
                        @include loading();
                    }
                }
            }

            .edit-section{
                width: 100%;
                height: 88%;
                overflow-y: auto;
                padding: 10px;

                .loading,
                .alert{
                    width: 100%;
                    text-align: center;
                    padding: 5px;
                }

                .alert{
                    font-size: 12px;
                    color: white;
                }

                .success{
                    background-color: green;
                }

                .danger{
                    background-color: red;
                }

                .section{
                    @include section();
                }

                .info{
                    @include info();
                    margin-bottom: 10px;
                }

                .form-edit{
                    margin: 10px auto;

                    input,
                    textarea{
                        width: 90%;
                        margin: 10px auto;
                    }

                    .main-section{
                        display: flex;
                        justify-content: flex-start;
                        align-items: flex-start;
                        flex-wrap: wrap;
                        width: 100%;

                        .label{
                            margin-right: 10px;
                            font-size: 14px;
                            color: gray;
                        }
                        
                        .grey-button{
                            margin: 0 10px 10px 0;
                        }
                    }
                }

                .files{
                    display: inline-flex;
                    justify-content: space-around;
                    width: 100%;
                    font-size: 14px;
                    margin: 20px 0 10px;

                    .file{
                        padding: 5px;
                        border-radius: 10px;
                        background: gray;
                        color: mintcream;
                        cursor: pointer;

                        &:hover{
                            background: green;
                            transition: all .5s ease;
                        }
                    }

                    .active{
                        background: green;
                        transition: all .5s ease;
                    }
                }

                .actions{
                    display: inline-flex;
                    margin-bottom: 10px;

                    .action{
                        color: gray;
                        cursor: pointer;
                        padding: 5px;
                        margin: 0 10px 0 0;
                    }
                }

                .media-section{
                    width: 100%;
                    padding: 5px;
                    margin: 5px 0;
                    overflow-x: auto;
                    overflow-y: hidden;
                    display: inline-flex;

                    .media-item{
                        display: inline-flex;
                        justify-content: space-between;
                        align-items: center;
                        color: gray;
                        background-color: white;
                        width: 150px;
                        font-size: 10px;
                        padding: 5px;
                        cursor: pointer;
                        position: relative;
                        margin: 0 10px 0 0;

                        .item-info{
                            font-size: 11px;
                            max-width: 70%;
                            text-overflow: ellipsis;
                            overflow: hidden;
                            white-space: nowrap;
                        }
                        
                        .item-type{
                            font-size: 10px;
                        }

                        .item-clear{
                            @include close();
                            z-index: 1;
                        }
                    }
                }

                .file-preview{
                    width: 100%;
                    position: relative;

                    .edit{
                        position: absolute;
                        font-size: 14px;
                        top: 0;
                    }
                }

                .buttons{
                    margin-top: 10px;
                    width: 100%;
                    display: inline-flex;
                    justify-content: space-around;
                }
            }
        }

        .discusssion-request-section{

            .body{

                .search-section{
                    margin-bottom: 10px;
                }

                .search-types{
                    width: 100%;
                    display: inline-flex;
                    justify-content: space-around;
                    align-items: center;
                    flex-wrap: wrap;

                    .grey-button{
                        margin-bottom: 10px;
                    }
                }

                .accounts-section{
                    padding: 10px;
                    overflow-y: auto;
                    max-height: 400px;
                    width: 100%;
                    margin-top: 20px;

                    .no-participants{
                        width: 100%;
                        height: 100px;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        color: gray;
                        font-size: 14px;
                    }

                    .participant-badge{
                        margin-bottom: 10px;
                    }

                    .loading{
                        width: 100%;
                        text-align: center;
                    }

                    .show-more{
                        @include show-more();
                    }
                }
            }
        }
    }
</style>