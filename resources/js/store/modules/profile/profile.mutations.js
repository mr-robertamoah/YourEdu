import {
    addDataToProfileItemProperty, 
    spliceDataFromProfileItemProperty,
    spliceDataFromProfileItem,
    getItemTypeBasedOnText,
    getItemType,
    spliceDataFromProfileStateProperty,
    addDataToProfileStatePropertysProperty,
    spliceDataFromProfileStatePropertysProperty,
    getParticipantOrMarkerData,
    getParticipantOrMarkerProperty
} from '../../../services/mutation.service'

const mutations = {
    CLEAR_MEDIA(state){
        state.media = []
        state.mediaType = ''
        state.moreMedia = false
    },
    PUBLIC_MEDIA_SUCCESS(state, data){
        if (data.media === 'images') {
            state.profile.images.unshift(data.main.media)
        } else if (data.media === 'videos') {
            state.profile.videos.unshift(data.main.media)
        } else if (data.media === 'audios') {
            state.profile.audios.unshift(data.main.media)
        }
    },
    GET_MEDIA_SUCCESS(state,media){
        let {mainData, data} = media
        console.log('get media', state.media)
        if (data.media === 'images') {
            state.mediaType = 'image'
        } else if (data.media === 'videos') {
            state.mediaType = 'video'
        } else if (data.media === 'audios') {
            state.mediaType = 'audio'
        }
        if (mainData.links.next) {
            state.moreMedia = true
        } else {
            state.moreMedia = false
        }
        state.media.push(...mainData.data)
    },
    GET_PROFILE_SUCCESS(state,data){
        state.message = 'successfully got the requested profile'
        state.account = data.account
        state.profile = data.profile
    },
    UPDATE_PROFILE_SUCCESS(state,data){
        state.message = 'successfully updated your profile'
        state.profile = data.profile
    },
    UPDATE_PROFILE_PIC_SUCCESS(state,data){
        state.profile.url = data.image.url
    },
    UPLOAD_FILE_SUCCESS(state,data){
        if (data.hasOwnProperty('image')) {
            state.profile.images.unshift(data.image)
        } else if (data.hasOwnProperty('video')) {
            state.profile.videos.unshift(data.video)
        } else if (data.hasOwnProperty('audio')) {
            state.profile.audios.unshift(data.audio)
        }
    },
    PROFILE_FAILURE(state, msg){
        state.message = msg
    },
    CLEAR_PROFILE_MSG(state){
        state.message = null
    },
    ADD_INFO_SUCCESS(state,responseData,data){
        if (data.hasOwnProperty('email')) {
            state.profile.owner.emails.unshift(responseData.email)
        } else if (data.hasOwnProperty('social')){
            state.profile.socials.unshift(responseData.social)
        } else if (data.hasOwnProperty('phone')){
            state.profile.owner.phoneNumbers.unshift(responseData.phone)
        }
    },
    MARK_INFO_SUCCESS(state,data){
        if (data.item === 'email') {
            let emailIndex = state.profile.owner.emails.findIndex(email=>{
                return email.id === data.id
            })

            if (emailIndex > -1) {
                // console.log(state.profile.owner.emails[emailIndex].show)
                state.profile.owner.emails[emailIndex].show = !state.profile.owner.emails[emailIndex].show
            }
        } else if (data.item === 'social'){
            let socailIndex = state.profile.socials.findIndex(social=>{
                return social.id === data.id
            })

            if (socailIndex > -1) {
                state.profile.socails[socailIndex].show = !state.profile.socails[socailIndex].show
            }
        } else if (data.item === 'phone'){
            let phoneIndex = state.profile.owner.phoneNumbers.findIndex(phone=>{
                return phone.id === data.id
            })

            if (phoneIndex > -1) {
                state.profile.owner.phoneNumbers[phoneIndex].show = !state.profile.owner.phoneNumbers[phoneIndex].show
            }
        }
    },
    REMOVE_INFO_SUCCESS(state,data){
        if (data.item === 'email') {
            let emailIndex = state.profile.owner.emails.findIndex(email=>{
                return email.id === data.id
            })

            if (emailIndex > -1) {
                state.profile.owner.emails.splice(emailIndex,1)
            }
        } else if (data.item === 'social'){
            let socailIndex = state.profile.socials.findIndex(social=>{
                return social.id === data.id
            })

            if (socailIndex > -1) {
                state.profile.socails.splice(socailIndex,1)
            }
        } else if (data.item === 'phone'){
            let phoneIndex = state.profile.owner.phoneNumbers.findIndex(phone=>{
                return phone.id === data.id
            })

            if (phoneIndex > -1) {
                state.profile.owner.phoneNumbers.splice(phoneIndex,1)
            }
        }
    },

    ////////////////////////////////////////////////////////// follow

    FOLLOW_SUCCESS(state,data){
        if (state.profile) {
            state.profile.follows.unshift(data.follow)
        }
    },
    UNFOLLOW_SUCCESS(state,data){
        if (state.profile) {
            let followIndex = state.profile.follows.findIndex(follow=>{
                return follow.id === data.followId
            })

            if (followIndex > -1) {
                state.profile.follows.splice(followIndex,1)
            }
        }
    },

    /////////////////////////////////////////////////////////// discussions
    DISCUSSION_CREATE_SUCCESS(state,data){
        state.message = 'discussion successful'
        state.posts.unshift(data.discussion)
    },
    DISCUSSION_UPDATE_SUCCESS(state,data){

        spliceDataFromProfileItem({
            state,
            data: data.discussion,
            fn: (item) => item.id == data.discussion.id && item.isDiscussion
        })
    },
    DISCUSSION_DELETE_SUCCESS(state,data){

        let successful = spliceDataFromProfileItem({
            state,
            data: null,
            fn: (item) => item.id == data.discussionId && item.isDiscussion
        })

        if (! successful) {
            state.message = 'deletion of discussion was unsuccessfully 😕.'
            return
        }

        state.message = 'successfully deleted discussion 😎.'
    },
    CREATE_ITEM_PARTICIPANT(state,data){

        addDataToProfileItemProperty({
            state,
            property: data.participant ? 'participants' : 'markers',
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            data: data.participant ? data.participant : data.marker,
            methodType: 'push'
        })
    },
    ADD_DATA_TO_ITEM_PROPERTY(state,data){

        addDataToProfileItemProperty({
            state,
            property: data.property,
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            data: data.data,
            methodType: 'push'
        })
    },
    CREATE_PENDING_ITEM_PARTICIPANT(state,data){

        addDataToProfileItemProperty({
            state,
            property: 'pendingParticipants',
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            data: data.pendingParticipant,
            methodType: 'push'
        })
    },
    ADD_TIMER_TO_ITEM(state, data)
    {
        if (data.item.isAssessment) {
            data.item.timer = data.timer
            return
        }

        addDataToProfileItemProperty({
            state,
            property: 'timer',
            sub: 'assessmentSections',
            subId: data.item.id,
            data: data.timer
        })
    },
    NEW_DISCUSSION(state,discussion){
        state.posts.unshift(discussion)
    },
    REPLACE_DISCUSSION(state,discussion){

        spliceDataFromProfileItem({
            state,
            data: discussion,
            fn: (item) => item.id == discussion.id && item.isDiscussion
        })
    },
    REMOVE_DISCUSSION(state,info){

        spliceDataFromProfileItem({
            state,
            data: null,
            fn: (item) => item.id == info.discussionId && item.isDiscussion
        })
    },
    NEW_ITEM_PARTICIPANT(state,data){

        addDataToProfileItemProperty({
            state,
            data: data.participant,
            fn: (item) => data.itemId && item[getItemType(data.item)],
            methodType: 'push',
            property: 'participants',
        })
    },
    UPDATE_ITEM_PARTICIPANT(state,data){

        spliceDataFromProfileItemProperty({
            state,
            data: getParticipantOrMarkerData(data),
            property: getParticipantOrMarkerProperty(data),
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            profileFn: (participant) => participant.userId === data.participant.userId,
        })
    },
    REMOVE_ITEM_PARTICIPANT(state,data){

        spliceDataFromProfileItemProperty({
            state,
            data: null,
            property: data.type ? `${data.type}s` : 'participants' ,
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            profileFn: (participant) => participant.userId === data.userId,
        })
    },
    NEW_ITEM_PENDING_PARTICIPANT(state,data){

        addDataToProfileItemProperty({
            state,
            data: data.pendingParticipant,
            property: 'pendingParticipants',
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            methodType: 'push'
        })
    },
    REMOVE_ITEM_PENDING_PARTICIPANT(state,data){

        spliceDataFromProfileItemProperty({
            state,
            data: null,
            property: 'pendingParticipants',
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)],
            profileFn: (participant) => participant.userId == data.userId,
        })
    },

    /////////////////////////////////////////////////////////// posts
    CLEAR_POSTS(state){
        state.posts = []
        state.nextPage = 0
    },
    POST_CREATE_SUCCESS(state,data){
        state.message = 'post successful'
        state.posts.unshift(data.post)
    },
    POST_UPDATE_SUCCESS(state,data){
        
        spliceDataFromProfileItem({
            state,
            data: data.post,
            fn: (item) => item.id === data.post.id && item.isPost
        })
    },
    POST_DELETE_SUCCESS(state,data){

        let successful = spliceDataFromProfileItem({
            state,
            data: null,
            fn: (item) => data.postId && item.isPost
        })

        if (! successful) {
            state.message = 'deletion of post was unsuccessful 😕.'
            return
        }
        
        state.message = 'successfully deleted post 😎.'
    },
    POSTS_SUCCESS(state,data){
        if (data.data.length) {
            state.posts.push(...data.data)
            state.postNextPage = data.hasOwnProperty('meta') ?
                data.meta.current_page + 1 : null
        } else{
            state.postDone = true
        }
    },
    NEW_POST(state,post){
        state.posts.unshift(post)
    },
    REPLACE_POST(state,post){

        spliceDataFromProfileItem({
            state,
            data: post,
            fn: (item) => item.id === post.id && item.isPost
        })
    },
    REMOVE_POST(state,postInfo){

        spliceDataFromProfileItem({
            state,
            data: null,
            fn: (item) => item.id == Number(postInfo.postId) && item.isPost
        })
    },
    POST_START(state){
        state.postingStatus = true
    },
    POST_END(state){
        state.postingStatus = false
    },
    //////////////////////////////////////////////////////////
    LOADING_START(state){
        state.loading = true
    },
    LOADING_END(state){
        state.loading = false
    },

    ////////////////////////////////////////////////answers

    ANSWER_CREATE_SUCCESS(state,data){

        if (! data.response.hasOwnProperty('answer')) {
            return
        }

        let postIndex = state.posts.findIndex(post=>{
            return post.id === data.data.postId
        })
        if (postIndex > -1) {
            state.posts[postIndex].type[0].answers = data.response.answer
            state.posts[postIndex].type[0].answers_number += 1
        }
    },

    ///////////////////////////////////////////// comments
    COMMENTING_START(state){
        state.commentingStatus = true
    },
    COMMENTING_END(state){
        state.commentingStatus = false
    },
    COMMENTS_GET_SUCCESS(state, data){
        if (data.data.length) {
            state.comments.push(...data.data)
            state.commentNextPage = data.hasOwnProperty('meta') ?
                data.meta.current_page + 1 : null
        } else{
            state.commentDone = true
        }
        
    },
    COMMENT_GET_SUCCESS(state, data){
        // state.comment
    },
    COMMENT_UPDATE_SUCCESS(state,data){
        
        if (data.item === 'comment') {
            
            return spliceDataFromProfileStateProperty({
                data: data.comment,
                state,
                fn: (item) => item.id == data.comment.id,
                property: 'comments'
            })
        }

        spliceDataFromProfileItemProperty({
            fn: (item) => item.id === data.itemId && item[getItemType(data.item)],
            propertyFn: (item) => item.id === data.comment.id,
            data: data.comment,
            state,
            property: 'comments'
        })
    },
    COMMENT_DELETE_SUCCESS(state,data){

        if (data.item === 'comment') {

            return spliceDataFromProfileStateProperty({
                data: null,
                state,
                fn: (item) => item.id == data.commentId,
                property: 'comments'
            })
        }

        spliceDataFromProfileItemProperty({
            fn: (item) => item.id === data.itemId && item[getItemTypet(data.item)],
            propertyFn: (item) => item.id === data.commentId,
            data: null,
            state,
            property: 'comments'
        })
    },
    COMMENT_SUCCESS(state, data){

        if (data.item === 'comment') {
            state.comments?.unshift(data.comment)
            return
        }

        addDataToProfileItemProperty({
            state,
            data: data.comment,
            methodType: 'unshift',
            fn: (item) => item.id == data.itemId && 
                item[getItemType(data.item)],
            property: 'comments'
        })
    },
    COMMENT_FAILURE(state, data){

    },
    COMMENTS_CLEAR(state){
        state.comments = []
        state.commentNextPage = 0
    },
    NEW_COMMENT(state, data){

        addDataToProfileItemProperty({
            state,
            data: data.comment,
            fn: (item) => item.id === Number(data.itemId) && item[getItemType(data.item)],
            property: 'comments',
            methodType: 'unshift',
        })
    },
    REPLACE_COMMENT(state, data){

        spliceDataFromProfileItemProperty({
            state,
            data: data.comment,
            fn: (item) => item.id === Number(data.itemId) && item[getItemType(data.item)],
            propertyFn: (item) => item.id === data.comment.id,
            property: 'comments',
        })
    },
    REMOVE_COMMENT(state, data){

        spliceDataFromProfileItemProperty({
            state,
            data: null,
            fn: (item) => item.id === Number(data.itemId) && item[getItemType(data.item)],
            propertyFn: (item) => item.id === Number(data.commentId),
            property: 'comments',
        })
    },
    //////////////////////////////////////// saves
    
    SAVE_CREATE_SUCCESS(state, data){

        addDataToProfileItemProperty({
            data: data.save,
            property: 'saves',
            fn: (item) => item.id === data.itemId && item[getItemType(data.item)],
            methodType: 'push',
            state,
        })
    },
    SAVE_DELETE_SUCCESS(state, data){

        spliceDataFromProfileItemProperty({
            state,
            data: null,
            property: 'saves',
            propertyFn: (save) => save.id === data.saveId,
            fn: (item) => item.id === data.itemId && item[getItemType(data.item)],
        })
    },

    //////////////////////////////////////// likes
    
    LIKE_CREATE_SUCCESS(state, data){

        addDataToProfileItemProperty({
            state,
            property: 'likes',
            data: data.like,
            fn: (item) => item.id === data.itemId && item[getItemType(data.item)],
            methodType: 'push'
        })
    
    },
    LIKE_DELETE_SUCCESS(state, data){

        spliceDataFromProfileItemProperty({
            state,
            data: null,
            property: 'likes',
            fn: (item) => item.id === data.itemId && item[getItemType(data.item)],
            propertyFn: (like) => like.id === data.likeId,
        })
    },
    NEW_LIKE(state,data){
        
        if (data.item === 'comment') {

            return addDataToProfileStatePropertysProperty({
                state,
                stateProperty: 'comments',
                property: 'likes',
                methodType: 'push',
                data: data.like,
                fn: (comment) => comment.id === Number(data.itemId)
            })
        }

        addDataToProfileItemProperty({
            state,
            data: data.like,
            property: 'likes',
            methodType: 'push',
            fn: (item) => item.id === Number(data.itemId) && item[getItemType(data.item)]
        })
    },
    REMOVE_LIKE(state,data){
        
        if (data.item === 'comment') {

            return spliceDataFromProfileStatePropertysProperty({
                state,
                stateProperty: 'comments',
                property: 'likes',
                data: null,
                fn: (comment) => comment.id === Number(data.itemId),
                propertyFn: (like) => like.id === Number(data.likeId),
            })
        }

        spliceDataFromProfileItemProperty({
            state,
            property: 'likes',
            data: null,
            fn: (item) => item.id === Number(data.itemId) && item[getItemType(data.item)],
            propertyFn: (like) => like.id === Number(data.likeId),
        })
    },

    
    ////////////////////////////////// flag

    FLAG_CREATE_SUCCESS(state, data){
        if (! data.items.includes(data.item)) {
            state.profile.flags.unshift(data.flag)
            return
        }

        spliceDataFromProfileItem({
            state,
            data: null,
            fn: (item) => item.id === data.itemId && item[getItemType(data.item)]
        })
    },
    FLAG_DELETE_SUCCESS(state, data) {
        
        if (! data.items.includes(data.item)) {

            return spliceDataFromProfileStatePropertysProperty({
                state,
                data: null,
                property: 'flags',
                stateProperty: 'profile',
                fn: null,
                propertyFn: (flag) => flag.id === data.flagId
            })
        }

        spliceDataFromProfileItemProperty({
            state,
            data: null,
            property: 'flags',
            fn: (item) => item.id === data.itemId && item[getItemType(data.item)],
            propertyFn: (flag) => flag.id === data.flagId,
        })

    },
    NEW_FLAG(state, data) {
        if (! data.items.includes(data.item)) {
            return
        }

        addDataToProfileItemProperty({
            state,
            data: data.flag,
            property: 'flags',
            methodType: 'push',
            fn: (item) => item.id == data.itemId && item[getItemType(data.item)]
        })
    },

    //////////////////////////////////////// attachments
    
    ATTACHMENT_CREATE_SUCCESS(state, data){

        addDataToProfileItemProperty({
            state,
            data: data.attachment,
            property: 'attachments',
            methodType: 'push',
            fn: (item) => item.id === data.item && item[getItemType(data.item)]
        })
    },
    ATTACHMENT_DELETE_SUCCESS(state, data) {
        
        spliceDataFromProfileItemProperty({
            state,
            data: null,
            property: 'attachments',
            propertyFn: (attachment) => attachment.id === data.attachmentId,
            fn: (item) => item.id === data.itemId && item[getItemType(data.item)],
        })
    },
    NEW_ATTACHMENT(state,data){

        addDataToProfileItemProperty({
            state,
            data: data.attachment,
            property: 'attachments',
            methodType: 'push',
            fn: (item) => item.id == data.itemId &&
                item[getItemType(data.item)]
        })
    },
    REMOVE_ATTACHMENT(state,data){

        spliceDataFromProfileItemProperty({
            state,
            data: null,
            property: 'attachments',
            propertyFn: (item) => item.id == data.attachmentId,
            fn: (item) => item.id == data.itemId &&
                item[getItemType(data.item)],
        })
    },
    ///////////////////////////
    ASSESSMENT_CREATE_SUCCESS(state, assessment){ 
        state.posts.unshift(assessment)
    },
    DISCUSSION_CREATE_SUCCESS(state,data){
        state.message = 'discussion successful'
        state.posts.unshift(data.discussion)
    },
    ASSESSMENT_UPDATE_SUCCESS(state,data){

        spliceDataFromProfileItem({
            state,
            data: data.assessment,
            fn: (item) => item.id == data.assessment.id && item.isAssessment
        })
    },
    ASSESSMENT_DELETE_SUCCESS(state,data){

        spliceDataFromProfileItem({
            state,
            data: null,
            fn: (item) => item.id == data.assessmentId && item.isAssessment
        })
    },

    ////////////////////////////////////////
    SET_ACTIVE_PROFILE(state, data){
        state.activeProfile = data
    },
}

export default mutations