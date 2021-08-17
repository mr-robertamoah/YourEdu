import {
    addDataToHomeItem,
    addDataToHomeItemProperty, 
    addHomeItemData, 
    getItemType,
    getParticipantOrMarkerData,
    spliceDataFromHomeItem,
    spliceDataFromHomeItemProperty
} from '../../../services/mutation.service'

const modalOnlyItems = ['comment', 'answer']

const mutations = {
        
    //////////////////////////////////////////////////////////
    LOADING_START(state){
        state.loading = true
    },
    LOADING_END(state){
        state.loading = false
    },

    ///////////////////////////
    ASSESSMENT_CREATE_SUCCESS(state, assessment) {
        
        addDataToHomeItem({
            state,
            data: data.assessment,
            itemType: 'assessment',
            hasMine: true
        })
    },
    ASSESSMENT_UPDATE_SUCCESS(state, data) {
        
        spliceDataFromHomeItem({
            state,
            hasMine: true,
            data: data.assessment,
            itemType: 'assessment',
            unique: true,
            fn: (item) => item.id == data.assessment.id && item.isAssessment,
        })
    },
    ASSESSMENT_DELETE_SUCCESS(state, data) {
        
        spliceDataFromHomeItem({
            state,
            hasMine: true,
            data: null,
            itemType: 'assessment',
            unique: true,
            fn: (item) => item.id == data.assessmentId && item.isAssessment,
        })
    },
    NEW_ASSESSMENT(state, data) {
        
        addDataToHomeItem({
            state,
            data: data.discussion,
            itemType: 'discussion',
            followers: data.userFollowers,
            followings: data.userFollowings,
        })
    },
    REPLACE_ASSESSMENT(state, data) {
        
        spliceDataFromHomeItem({
            state,
            itemType: 'discussion',
            data: data.discussion,
            unique: true,
            fn: (item) => item.id == data.discussion.id && item.isDiscussion,
        })
    },
    REMOVE_ASSESSMENT(state, data) {
        
        spliceDataFromHomeItem({
            state,
            itemType: 'discussion',
            data: null,
            unique: true,
            fn: (item) => item.id == data.discussionId && item.isDiscussion,
        })
    },
    ADD_TIMER_TO_ITEM(state, data)
    {
        addDataToHomeItemProperty({
            state,
            property: 'timer',
            data: data.timer,
            itemType: 'assessment',
            hasMine: true,
            equate: true,
            fn: (item) => (item.id == data.item.id || item.id == data.item.assessmentId) && item.isAssessment,
            sub: data.item.isAssessment ? null : 'assessmentSections',
            subId: data.item.isAssessment ? null : data.item.id,
        })
    },
    ////////////////////////////////////////////////////discussions
    DISCUSSION_CREATE_SUCCESS(state, data){ //for now, discussions will be shown in main posts
        
        addDataToHomeItem({
            state,
            data: data.discussion,
            itemType: 'discussion',
            hasMine: true
        })
    },
    DISCUSSION_UPDATE_SUCCESS(state, data) {
        
        spliceDataFromHomeItem({
            state,
            hasMine: true,
            data: data.discussion,
            itemType: 'discussion',
            unique: true,
            fn: (item) => item.id == data.discussion.id && item.isDiscussion,
        })
    },
    DISCUSSION_DELETE_SUCCESS(state, data) {
        
        spliceDataFromHomeItem({
            state,
            hasMine: true,
            data: null,
            itemType: 'discussion',
            unique: true,
            fn: (item) => item.id == data.discussionId && item.isDiscussion,
        })
    },
    CREATE_PENDING_ITEM_PARTICIPANT(state,data){

        addDataToHomeItemProperty({
            state, 
            property: 'pendingParticipants', 
            itemType: data.item, 
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)], 
            data: data.pendingParticipant,
            methodType: 'push',
            hasMine: true
        })
    },
    CREATE_ITEM_PARTICIPANT(state,data){

        addDataToHomeItemProperty({
            state, 
            property: data.participant ? 'participants' : 'markers',
            itemType: data.item, 
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)], 
            data: data.participant ? data.participant : data.marker,
            methodType: 'push',
            hasMine: true
        })
    },
    ADD_DATA_TO_ITEM_PROPERTY(state,data){

        addDataToHomeItemProperty({
            state, 
            property: data.property,
            itemType: data.item, 
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)], 
            data: data.data,
            methodType: 'push',
            hasMine: true
        })
    },
    //for socket updates
    NEW_DISCUSSION(state, data) {
        
        addDataToHomeItem({
            state,
            data: data.discussion,
            itemType: 'discussion',
            followers: data.userFollowers,
            followings: data.userFollowings,
        })
    },
    REPLACE_DISCUSSION(state, data) {
        
        spliceDataFromHomeItem({
            state,
            itemType: 'discussion',
            data: data.discussion,
            unique: true,
            fn: (item) => item.id == data.discussion.id && item.isDiscussion,
        })
    },
    REMOVE_DISCUSSION(state, data) {
        
        spliceDataFromHomeItem({
            state,
            itemType: 'discussion',
            data: null,
            unique: true,
            fn: (item) => item.id == data.discussionId && item.isDiscussion,
        })
    },
    NEW_ITEM_PARTICIPANT(state,data){

        addDataToHomeItemProperty({
            state,
            data: getParticipantOrMarkerData(data),
            itemType: data.item,
            property: getParticipantOrMarkerProperty(data),
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            methodType: 'push'
        })
    },
    UPDATE_ITEM_PARTICIPANT(state, data) {
        
        spliceDataFromHomeItemProperty({
            state,
            unique: true,
            property: 'participants',
            itemType: data.item,
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            propertyFn: (item) => item.userId == data.participant.userId,
            data: data.participant,
            hasMine: true
        })
    },
    REMOVE_ITEM_PARTICIPANT(state, data) {

        spliceDataFromHomeItemProperty({
            state,
            property: data.type ? `${data.type}s` : 'participants',
            itemType: data.item,
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            propertyFn: (item) => item.userId == data.userId,
            data: null,
            hasMine: true
        })
    },
    NEW_ITEM_PENDING_PARTICIPANT(state,data){

        addDataToHomeItemProperty({
            state,
            data: data.pendingParticipant,
            itemType: data.item,
            property: 'pendingParticipants',
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            methodType: 'push'
        })
    },
    REMOVE_ITEM_PENDING_PARTICIPANT(state, data) {
        
        spliceDataFromHomeItemProperty({
            state,
            property: 'pendingParticipants',
            itemType: data.item,
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            propertyFn: (item) => item.userId == data.userId,
            data: null,
            hasMine: true
        })
    },

    ////////////////////////////////////////////////////posts
    POST_CREATE_SUCCESS(state, data) {
        
        addDataToHomeItem({
            state,
            data: data.post,
            postType: data.post.typeName,
            itemType: 'post',
            hasMine: true
        })
    },
    POST_UPDATE_SUCCESS(state, data) {
        
        spliceDataFromHomeItem({
            state,
            data: data.post,
            itemType: 'post',
            postType: data.post.typeName,
            unique: true,
            fn: (item) => item.id == data.post.id && item.isPost,
            hasMine: true
        })
    },
    POST_DELETE_SUCCESS(state, data) {
        
        spliceDataFromHomeItem({
            state,
            data: null,
            itemType: 'post',
            postType: data.postType,
            fn: (item) => item.id == data.postId && item.isPost,
            hasMine: true
        })
    },
    POSTS_SUCCESS(state,main){
        let {nextPage, params, data} = main

        addHomeItemData({
            state,
            data,
            currentPage: nextPage,
            itemType: params.type.slice(0, params.type.length - 1),
            params
        })
    },
    POST_TYPES_SUCCESS(state,main){
        let { nextPage, params, data } = main
        
        addHomeItemData({
            state,
            data,
            currentPage: nextPage,
            itemType: params.postType.slice(0, params.postType.length - 1),
            params
        })
    },
    NEW_ACCOUNT_ITEM(state, data) {

        addDataToHomeItem({
            state,
            data: data.post ? data.post : data.discussion ? data.discussion : data.assessment ? data.assessment : null,
            itemType: data.post ? 'post' : data.discussion ? 'discussion' : data.assessment ? 'assessment' : null,
            isAFollower: data.isAFollower,
            isAFollowing: data.isAFollowing,
            postType: data.post?.typeName
        })
    },
    NEW_ITEM(state, data) {

        addDataToHomeItem({
            state,
            data: data.post ? data.post : data.discussion ? data.discussion : data.assessment ? data.assessment : null,
            itemType: data.post ? 'post' : data.discussion ? 'discussion' : data.assessment ? 'assessment' : null,
            followers: data.userFollowers,
            followings: data.userFollowings,
            postType: data.post?.typeName
        })
    },
    CLEAR_POSTS(state){
        state.posts = []
    },
    CLEAR_POSTS_ATTACHMENTS(state){
        state.postsAttachments = []
    },
    CLEAR_QUESTIONS_ATTACHMENTS(state){
        state.questionsAttachments = []
    },
    CLEAR_POEMS_ATTACHMENTS(state){
        state.poemsAttachments = []
    },
    CLEAR_ACTIVITIES_ATTACHMENTS(state){
        state.activitiesAttachments = []
    },
    CLEAR_RIDDLES_ATTACHMENTS(state){
        state.riddlesAttachments = []
    },
    CLEAR_BOOKS_ATTACHMENTS(state){
        state.booksAttachments = []
    },
    NEW_POST(state, data) {
        
        addDataToHomeItem({
            followers: data.userFollowers,
            followings: data.userFollowings,
            data: data.post,
            postType: data.post.typeName,
            state,
            itemType: 'post'
        })
    },
    REPLACE_POST(state, data) {

        spliceDataFromHomeItem({
            data: null,
            itemType: 'post',
            state,
            postType: data.post.typeName,
            fn: (item) => item.id == data.post.id && item.isPost,
            unique: true
        })
    },
    REMOVE_POST(state,data){

        spliceDataFromHomeItem({
            data: null,
            itemType: 'post',
            state,
            postType: data.postType,
            fn: (item) => item.id == data.postId && item.isPost,
        })
    },
    ////////////////////////////////////comments

    COMMENT_UPDATE_SUCCESS(state,data){
        if (modalOnlyItems.includes(data.item)) {
            return
        }

        spliceDataFromHomeItemProperty({
            data: data.comment,
            itemType: data.item,
            hasMine: true,
            state,
            property: 'comments',
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            propertyFn: (item) => item.id == data.comment.id,
        })
    },
    COMMENT_DELETE_SUCCESS(state,data){
        if (modalOnlyItems.includes(data.item)) {
            return
        }

        spliceDataFromHomeItemProperty({
            data: null,
            itemType: data.item,
            hasMine: true,
            state,
            property: 'comments',
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            propertyFn: (item) => item.id == data.commentId,
        })
    },
    COMMENT_SUCCESS(state, data) {
        if (modalOnlyItems.includes(data.item)) {
            return
        }

        addDataToHomeItemProperty({
            state, 
            property: 'comments', 
            itemType: data.item, 
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)], 
            data: data.comment,
            hasMine: false,
            methodType: 'unshift',
        })
    },
    NEW_COMMENT(state,data){

        addDataToHomeItemProperty({
            state, 
            property: 'comments', 
            itemType: data.item, 
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)], 
            data: data.comment,
            hasMine: false,
            methodType: 'unshift',
        })
    },
    REPLACE_COMMENT(state,data){

        spliceDataFromHomeItemProperty({
            data: data.comment,
            itemType: data.item,
            state,
            property: 'comments',
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            propertyFn: (item) => item.id == data.comment.id,
            unique: true
        })
    },
    REMOVE_COMMENT(state,data){

        spliceDataFromHomeItemProperty({
            data: null,
            itemType: data.item,
            state,
            property: 'comments',
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            propertyFn: (item) => item.id == data.commentId,
        })
    },        
    
    /////////////////////////////////////////saves

    SAVE_CREATE_SUCCESS(state, data){
            
        addDataToHomeItemProperty({
            state, 
            property: 'saves', 
            itemType: data.item, 
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)], 
            data: data.save,
            hasMine: false,
            methodType: 'push',
            postType: data.postType
        })
    },
    SAVE_DELETE_SUCCESS(state, data){

        spliceDataFromHomeItemProperty({
            itemType: data.item,
            property: 'saves',
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            postType: data.postType,
            state,
            data: null,
            propertyFn: (item) => item.id == data.saveId
        })
    },

    ///////////////////////////////////flags

    FLAG_CREATE_SUCCESS(state, data){

        addDataToHomeItemProperty({
            state, 
            property: 'flags', 
            itemType: data.item, 
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)], 
            data: data.flag,
            hasMine: false,
            methodType: 'push',
            postType: data.postType
        })
    },
    FLAG_DELETE_SUCCESS(state, data){

        spliceDataFromHomeItemProperty({
            data: null,
            itemType: data.item,
            state,
            property: 'flags',
            postType: data.postType,
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            propertyFn: (item) => item.id == data.flagId,
        })
    },
    NEW_FLAG(state,data){

        addDataToHomeItemProperty({
            state,
            data: data.flag,
            itemType: data.item,
            postType: data.postType,
            hasMine: true,
            methodType: 'push',
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)]
        })
    },

    ///////////////////////////////////likes

    LIKE_CREATE_SUCCESS(state, data){
        
        addDataToHomeItemProperty({
            state, 
            property: 'likes', 
            itemType: data.item, 
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)], 
            data: data.like,
            hasMine: false,
            methodType: 'push',
            postType: data.postType
        })
    },
    LIKE_DELETE_SUCCESS(state, data){

        spliceDataFromHomeItemProperty({
            data: null,
            itemType: data.item,
            state,
            hasMine: true,
            property: 'likes',
            postType: data.postType,
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            propertyFn: (item) => item.id == data.likeId,
        })
    },
    NEW_LIKE(state,data){

        addDataToHomeItemProperty({
            state, 
            property: 'likes', 
            itemType: data.item, 
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)], 
            data: data.like,
            hasMine: false,
            methodType: 'push',
            postType: data.postType
        })
    },
    REMOVE_LIKE(state,data){

        spliceDataFromHomeItemProperty({
            data: null,
            itemType: data.item,
            state,
            property: 'likes',
            postType: data.postType,
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            propertyFn: (item) => item.id == data.likeId,
        })
    },

    ///////////////////////////////////attachment

    ATTACHMENT_CREATE_SUCCESS(state, data) {
        
        addDataToHomeItemProperty({
            data: data.attachment,
            hasMine: true,
            postType: data.postType,
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            property: 'attachments',
            methodType: 'push',
            state,
            itemType: data.item
        })
    },
    ATTACHMENT_DELETE_SUCCESS(state, data) {
        
        spliceDataFromHomeItemProperty({
            property: 'attachments',
            itemType: data.item,
            hasMine: true,
            data: null,
            postType: data.postType,
            state,
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            propertyFn: (item) => item.id == data.attachmentId,
        })
    },
    NEW_ATTACHMENT(state, data) {
        
        addDataToHomeItemProperty({
            state,
            data: data.attachment,
            property: 'attachments',
            postType: data.postType,
            hasMine: true,
            methodType: 'push',
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            propertyFn: (item) => item.id == data.attachment.id,
        })
    },
    REMOVE_ATTACHMENT(state,data){

        spliceDataFromHomeItemProperty({
            property: 'attachments',
            itemType: data.item,
            data: null,
            postType: data.postType,
            state,
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            propertyFn: (item) => item.id == data.attachmentId,
        })
    },

}

export default mutations