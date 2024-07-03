import { default as _ } from 'lodash';

const postOnly =  ['post', 'book', 'poem', 'question', 'activitie', 'riddle']

function addDataToProfileItemProperty({
    state, property, fn, data, methodType , equate, sub, subId
}) {
    
    let index = state.posts.findIndex(item => fn(item))

    if (index === -1) {
        return false
    }

    if (sub) {
        addDataToPropertyOfSub({item: state.posts[index], sub, subId, data, property})
        return
    }

    if (! state.posts[index][property]) {
        return false
    }
    
    if (equate) {
        state.posts[index][property] = data
        return true
    }

    state.posts[index][property][methodType](data)
    return true
}

function findProfileItemAndAddData({}) {
    
}

function addDataToProfileStatePropertysProperty({state, stateProperty, property, fn, data, methodType}){
    
    if (! state[stateProperty] || state[stateProperty][0][property]) {
        return false
    }

    let index = state[stateProperty].findIndex(item => fn(item))

    if (index === -1) {
        return false
    }
    
    state[stateProperty][index][property][methodType](data)
    return true
}

function spliceDataFromProfileStateProperty({state, property, fn, data}){
    
    if (! state[property]) {
        return false
    }

    let index = state[property].findIndex(item => fn(item))

    return spliceItems({
        item: state[property],
        data,
        index
    })
}

function spliceDataFromProfileItemProperty({state, property, fn, data, propertyFn}){
    
    if (! state.posts[0][property]) {
        return false
    }

    let index = state.posts.findIndex(item => fn(item))

    if (index === -1) {
        return false
    }

    let propertyIndex = state.posts[index][property].findIndex(propertyFn(item))

    return spliceItems({
        items: state.posts[index][property],
        data,
        index: propertyIndex
    })
}

function spliceDataFromProfileStatePropertysProperty({state, stateProperty, property, fn, data, propertyFn}){
    
    if (! state[stateProperty]) {
        return false
    }

    let statePropertyItem = null

    if (fn) {
        
        let index = state[stateProperty].findIndex(item => fn(item))
    
        if (index === -1) {
            return false
        }

        statePropertyItem = state[stateProperty][index]
    }

    if (! fn) {
        statePropertyItem = state[stateProperty]
    }

    if (! statePropertyItem[property]) {
        return false
    }

    let propertyIndex = statePropertyItem[property].findIndex(propertyFn(item))

    return spliceItems({
        items: statePropertyItem[property],
        index: propertyIndex,
        data
    })
}

function spliceDataFromProfileItem({state, fn, data}){
    
    let index = state.posts.findIndex(item => fn(item))

    return spliceItems({
        data,
        index,
        items: state.posts
    })
}

function findHomeItemAndAddData({
    items, fn, property, methodType, data, equate, sub, subId
}) {
    let index = items.findIndex(item => fn(item))

    if (index === -1) {
        return
    }

    if (equate && !sub) {
        items[index][property] = data
        return
    }

    if (equate && sub) {
        addDataToPropertyOfSub({item: items[index], sub, subId, data, property})
        return
    }

    items[index][property][methodType](data)
}

function addDataToPropertyOfSub({item, sub, subId, data, property}) {
    if (!sub || !subId || !item) {
        return
    }

    let index = item[sub].findIndex(subItem => subItem.id == subId)
    
    if (index === -1) {
        return
    }

    item[sub][index][property] = data
}

function addDataToHomeItemProperty({
    state,
    property,
    itemType,
    fn,
    data,
    hasMine,
    methodType,
    postType,
    equate,
    sub,
    subId
})
{
    findHomeItemAndAddData({
        items: state.posts, fn, property, methodType, data, equate, sub, subId
    })

    if (itemType == 'post' && postType) {
        
        findHomeItemAndAddData({
            items: state[`${postType}s`], fn, property, methodType, data, equate, sub, subId
        })

        if (hasMine) {
            findHomeItemAndAddData({
                items: state[`${postType}sMine`], fn, property, methodType, data, equate, sub, subId
            })
        }

        findHomeItemAndAddData({
            items: state[`${postType}sFollowers`], fn, property, methodType, data, equate, sub, subId
        })

        findHomeItemAndAddData({
            items: state[`${postType}sFollowings`], fn, property, methodType, data, equate, sub, subId
        })

        if (state[`${postType}sAttachments`]) {
            findHomeItemAndAddData({
                items: state[`${postType}sAttachments`], fn, property, methodType, data, equate, sub, subId
            })
        }
    }

    if (itemType != 'post') {
        
        findHomeItemAndAddData({
            items: state[`${itemType}s`], fn, property, methodType, data, equate, sub, subId
        })
    }
    
    if (hasMine) {
        
        findHomeItemAndAddData({
            items: state[`${itemType}sMine`], fn, property, methodType, data, equate, sub, subId
        })
    }

    findHomeItemAndAddData({
        items: state[`${itemType}sFollowers`], fn, property, methodType, data, equate, sub, subId
    })

    findHomeItemAndAddData({
        items: state[`${itemType}sFollowings`], fn, property, methodType, data, equate, sub, subId
    })

    if (! state[`${itemType}sAttachments`]) {
        return
    }

    findHomeItemAndAddData({
        items: state[`${itemType}sAttachments`], fn, property, methodType, data, equate, sub, subId
    })
}

function spliceDataFromHomeItemProperty(
    { state, property, itemType, fn, data, postType, propertyFn, unique, hasMine }
) {
    if (itemType === 'activity') {
        itemType = 'activitie'
    }

    let propertyIndex

    let index = state.posts.findIndex(item => fn(item))
    if (index > -1) {
        propertyIndex = state.posts[index][property].findIndex(item => propertyFn(item))
        spliceItems({unique, items: state.posts[index][property], data, index: propertyIndex})
    }

    if (itemType == 'post' && postType) {
        if (postType === 'activity') {
            postType = 'activitie'
        }

        index = state[`${postType}s`].findIndex(item => fn(item))
        if (index > -1) {
            propertyIndex = state[`${postType}s`][index][property].findIndex(item => propertyFn(item))
            spliceItems({unique, items: state[`${postType}s`][index][property], data, index: propertyIndex})
        }

        index = state[`${postType}sMine`].findIndex(item => fn(item))
        if (index > -1) {
            propertyIndex = state[`${postType}sMine`][index][property].findIndex(item => propertyFn(item))
            spliceItems({unique, items: state[`${postType}sMine`][index][property], data, index: propertyIndex})
        }

        index = state[`${postType}sFollowers`].findIndex(item => fn(item))
        if (index > -1) {
            propertyIndex = state[`${postType}sFollowers`][index][property].findIndex(item => propertyFn(item))
            spliceItems({unique, items: state[`${postType}sFollowers`][index][property], data, index: propertyIndex})
        }

        index = state[`${postType}sFollowings`].findIndex(item => fn(item))
        if (index > -1) {
            propertyIndex = state[`${postType}sFollowings`][index][property].findIndex(item => propertyFn(item))
            spliceItems({unique, items: state[`${postType}sFollowings`][index][property], data, index: propertyIndex})
        }

        index = state[`${postType}sAttachments`].findIndex(item => fn(item))
        if (index > -1) {
            propertyIndex = state[`${postType}sAttachments`][index][property].findIndex(item => propertyFn(item))
            spliceItems({unique, items: state[`${postType}sAttachments`][index][property], data, index: propertyIndex})
        }
    }

    if (itemType !== 'post') {
        
        index = state[`${itemType}s`].findIndex(item => fn(item))
        if (index > -1) {
            propertyIndex = state[`${itemType}s`][index][property].findIndex(item => propertyFn(item))
            spliceItems({unique, items: state[`${itemType}s`][index][property], data, index: propertyIndex})
        }
    }
    
    if (hasMine) {
        index = state[`${itemType}sMine`].findIndex(item => fn(item))
        if (index > -1) {
            propertyIndex = state[`${itemType}sMine`][index][property].findIndex(item => propertyFn(item))
            spliceItems({unique, items: state[`${itemType}sMine`][index][property], data, index: propertyIndex})
        }
    }

    index = state[`${itemType}sFollowers`].findIndex(item => fn(item))
    if (index > -1) {
        propertyIndex = state[`${itemType}sFollowers`][index][property].findIndex(item => propertyFn(item))
        spliceItems({unique, items: state[`${itemType}sFollowers`][index][property], data, index: propertyIndex})
    }

    index = state[`${itemType}sFollowings`].findIndex(item => fn(item))
    if (index > -1) {
        propertyIndex = state[`${itemType}sFollowings`][index][property].findIndex(item => propertyFn(item))
        spliceItems({unique, items: state[`${itemType}sFollowings`][index][property], data, index: propertyIndex})
    }

    if (! state[`${itemType}sAttachments`]) {
        return
    }

    index = state[`${itemType}sAttachments`].findIndex(item => fn(item))
    if (index > -1) {
        propertyIndex = state[`${itemType}sAttachments`][index][property].findIndex(item => propertyFn(item))
        spliceItems({unique, items: state[`${itemType}sAttachments`][index][property], data, index: propertyIndex})
    }
}

function spliceDataFromHomeItem(
    { state, itemType, fn, data, postType, unique, hasMine }
) {
    if (itemType === 'activity') {
        itemType = 'activitie'
    }

    let index = state.posts.findIndex(item => fn(item))
    spliceItems({unique, items: state.posts, data, index})

    if (itemType == 'post' && postType) {
        if (postType === 'activity') {
            postType = 'activitie'
        }

        index = state[`${postType}s`].findIndex(item => fn(item))
        spliceItems({unique, items: state[`${postType}s`], data, index})

        index = state[`${postType}sMine`].findIndex(item => fn(item))
        spliceItems({unique, items: state[`${postType}sMine`], data, index})

        index = state[`${postType}sFollowers`].findIndex(item => fn(item))
        spliceItems({unique, items: state[`${postType}sFollowers`], data, index})

        index = state[`${postType}sFollowings`].findIndex(item => fn(item))
        spliceItems({unique, items: state[`${postType}sFollowings`], data, index})

        index = state[`${postType}sAttachments`].findIndex(item => fn(item))
        spliceItems({unique, items: state[`${postType}sAttachments`], data, index})
    }

    if (itemType !== 'post') {
        
        index = state[`${itemType}s`].findIndex(item => fn(item))
        spliceItems({unique, items: state[`${itemType}s`], data, index})
    }
    
    if (hasMine) {
        index = state[`${itemType}sMine`].findIndex(item => fn(item))
        spliceItems({unique, items: state[`${itemType}sMine`], data, index})
    }

    index = state[`${itemType}sFollowers`].findIndex(item => fn(item))
    spliceItems({unique, items: state[`${itemType}sFollowers`], data, index})

    index = state[`${itemType}sFollowings`].findIndex(item => fn(item))
    spliceItems({unique, items: state[`${itemType}sFollowings`], data, index})

    if (! state[`${itemType}sAttachments`]) {
        return
    }

    index = state[`${itemType}sAttachments`].findIndex(item => fn(item))
    spliceItems({unique, items: state[`${itemType}sAttachments`], data, index})
}

function addDataToHomeItem(
    { state, itemType, data, postType, hasMine, followers, followings,
        isAFollower, isAFollowing}
) {
    let account = getAccountFromData(data),
        follower = isAFollower != undefined ? isAFollower : isFollower({followers, account}),
        following = isAFollowing != undefined ? isAFollowing : isFollowing({followings, account})
    
    state.posts.unshift(getData(data, true))

    if (itemType !== 'post' && state[`${itemType}s`].length) {
        state[`${itemType}s`].unshift(getData(data, true))
    }
    
    if (hasMine && state[`${itemType}sMine`].length) {
        state[`${itemType}sMine`].unshift(getData(data, true))
    }

    if (follower && state[`${itemType}sFollowers`].length) { //will use isInViewSection function
        state[`${itemType}sFollowers`].unshift(getData(data, true))
    }

    if (following && state[`${itemType}sFollowings`].length) {
        state[`${itemType}sFollowings`].unshift(getData(data, true))
    } //add that for attachments
    
    if (itemType == 'post' && postType) {
        if (postType === 'activity') {
            postType = 'activitie'
        }

        if (follower && state[`${postType}sFollowers`].length) { 
            state[`${postType}sFollowers`].unshift(getData(data, true))
        }
        if (following && state[`${postType}sFollowings`].length) {
            state[`${postType}sFollowings`].unshift(getData(data, true))
        }
    }
}

function getParticipantOrMarkerData(data) {
    if (data.participant) {
        return data.participant
    }

    return data.marker
}

function getParticipantOrMarkerProperty(data) {
    if (data.participant) {
        return 'participants'
    }

    return 'markers'
}

function addHomeItemData({data, currentPage, state, params, itemType}) {
    if (! data.length) {
        state.postDone = true
        return
    }

    if (params.hasOwnProperty('mine')) { //use isCurrentViewSection function
        if (currentPage === 1) {
            state[`${itemType}sMine`] = data
            return 
        }
        state[`${itemType}sMine`].push(...data)
        return
    }
    
    if (params.hasOwnProperty('followers')) {
        if (currentPage === 1) {
            state[`${itemType}sFollowers`] = data
            return
        }
        state[`${itemType}sFollowers`].push(...data)
        return
    }
    
    if (params.hasOwnProperty('followings')) {
        if (currentPage === 1) {
            state[`${itemType}sFollowings`] = data
            return
        }
        state[`${itemType}sFollowings`].push(...data)
        return
    }
    
    if (params.hasOwnProperty('attachments')) {
        if (currentPage === 1) {
            state[`${itemType}sAttachments`] = data
            return
        }
        state[`${itemType}sAttachments`].push(...data)
        return
    }

    if (itemType != 'post') {
        if (currentPage === 1) {
            state[`${itemType}s`] = data
            return
        }
        state[`${itemType}s`].push(...data)
        return
    }

    if (currentPage === 1) {
        state.posts = data
        return
    }
    state.posts.push(...data)
}

function isInViewSection({state, section}) {
    if (! state) {
        return false
    }
    
    if (! section) {
        return false
    }

    let index = state.viewSections.findIndex(section)

    if (index > -1) {
        return true
    }

    return false
}

function isCurrentViewSection(state, text) {

    if (state.viewSections[state.viewSections.length - 1] === text) {
        return true;
    }

    return false
}

function getAccountFromData(data) {
    if (data.addedby) {
        return data.addedby
    }

    if (data.raisedby) {
        return data.raisedby
    }

    return null
}

function spliceItems({ items, index, data, unique }) {
    
    if (index === -1) {
        return false
    }

    if (data) {
        items.splice(index, 1, getData(data, unique))
        return true
    }

    items.splice(index, 1)
    return true
}

function getData(data, unique = false) {
    unique ? JSON.parse(JSON.stringify(data)) : data
}

function addItem({items, data, methodType, unique}) {
    items[methodType](getData(data, unique))
}

function getItemType(text) {

    if (['book', 'poem', 'riddle', 'activity', 'question'].includes(text)) {
        text = 'post'
    }

    return `is${_.capitalize(text)}`
}

function getItemTypeBasedOnText(text) {
    if (text.toLocaleLowerCase().includes('assessment')) {
        return 'isAssessment'
    }
    
    if (text.toLocaleLowerCase().includes('discussion')) {
        return 'isDiscussion'
    }
    
    return 'isPost'
}

function isFollower({ followers, account }) {
    if (! followers) {
        return false
    }

    if (! account) {
        return false
    }

    let followIndex = followers.findIndex(follower => {
        return follower.followedby.account === account.account &&
            follower.followedby.accountId === account.accountId
    })

    if (followIndex > -1) {
        return true
    }

    return false
}

function isFollowing({followings, account}) {
    let followIndex = followings.findIndex(following => {
        return following.followable.account === account.account &&
            following.followable.accountId === account.accountId
    })

    if (followIndex > -1) {
        return true
    }

    return false
}

export {
    addDataToProfileItemProperty, 
    addDataToHomeItemProperty,
    addDataToHomeItem,
    spliceDataFromProfileItemProperty,
    spliceDataFromProfileItem,
    getItemTypeBasedOnText,
    getItemType,
    spliceDataFromProfileStateProperty,
    spliceDataFromProfileStatePropertysProperty,
    addDataToProfileStatePropertysProperty,
    spliceDataFromHomeItemProperty,
    spliceDataFromHomeItem,
    addHomeItemData,
    getParticipantOrMarkerData,
    getParticipantOrMarkerProperty,
}