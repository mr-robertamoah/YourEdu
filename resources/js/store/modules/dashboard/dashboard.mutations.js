const mutations = {

    //status
    ADD_STATUS_MESSAGE(state,data){
        state.statusMessage = data
    },
    CLEAR_STATUS_MESSAGE(state){
        state.statusMessage = ''
    },
    //accounts
    GET_ACCOUNTS_SUCCESS(state,data){
        if (data.nextPage === 1) {
            state.accounts = data.accounts
        } else {
            state.accounts.push(...data.accounts)
        }
    },
    GET_ACCOUNT_ACTIVITIES_SUCCESS(state,data){
        if (data.nextPage === 1) {
            state.activities = data.activities
        } else {
            state.activities.push(...data.activities)
        }
    },
    CLEAR_ACCOUNT_ACTIVITIES(state){
        state.activities = []
    },
    //users
    GET_USERS_SUCCESS(state,data){
        if (data.nextPage === 1) {
            state.users = data.users
        } else {
            state.users.push(...data.users)
        }
    },
    //admins
    GET_ADMINS_SUCCESS(state,data){
        if (data.nextPage === 1) {
            state.admins = data.admins
        } else {
            state.admins.push(...data.admins)
        }
    },
    CHANGE_BAN_STATUS(state,account){
        let index 
        if (account.username) {
            index= state.users.findIndex(user=>{
                return user.id === account.id
            })
            if (index > -1) {
                state.users.splice(index,1,account)
            }
        } else {
            index = state.accounts.findIndex(acc=>{
                return acc.account === account.account && 
                    acc.accountId === account.accountId
            })
            if (index > -1) {
                state.accounts.splice(index,1,account)
            }
        }
    },
    DASHBOARD_ACCOUNT_SUCCESS(state,data){
        state.accountDetails = data.details
        state.currentAccount = data.data
    },
    //class
    ADD_NEW_CLASS(state,data){
        state.accountDetails.classes.unshift(data)
    },
    ADD_NEW_OWNED_CLASS(state,data){
        state.accountDetails.ownedClasses.unshift(data)
    },
    UPDATE_CLASS(state,data){
        let index = state.accountDetails.classes.findIndex(c=>{
            return c.id === data.id
        })
        if (index > -1) {
            state.accountDetails.classes.splice(index,1,data)
        }
    },
    UPDATE_OWNED_CLASS(state,data){
        let index = state.accountDetails.ownedClasses.findIndex(c=>{
            return c.id === data.id
        })
        if (index > -1) {
            state.accountDetails.ownedClasses.splice(index,1,data)
        }
    },
    REMOVE_CLASS(state,classId){
        let index = state.accountDetails.classes.findIndex(c=>{
            return c.id == classId
        })
        if (index > -1) {
            state.accountDetails.classes.splice(index,1)
        }
    },
    REMOVE_OWNED_CLASS(state,classId){
        let index = state.accountDetails.ownedClasses.findIndex(c=>{
            return c.id == classId
        })
        if (index > -1) {
            state.accountDetails.ownedClasses.splice(index,1)
        }
    },
    //extracurriculum
    ADD_NEW_EXTRACURRICULUM(state,data){
        state.accountDetails.extracurriculums.unshift(data)
    },
    ADD_NEW_OWNED_EXTRACURRICULUM(state,data){
        state.accountDetails.ownedExtracurriculums.unshift(data)
    },
    UPDATE_EXTRACURRICULUM(state,data){
        let index = state.accountDetails.extracurriculums.findIndex(c=>{
            return c.id === data.id
        })
        if (index > -1) {
            state.accountDetails.extracurriculums.splice(index,1,data)
        }
    },
    UPDATE_OWNED_EXTRACURRICULUM(state,data){
        let index = state.accountDetails.ownedExtracurriculums.findIndex(c=>{
            return c.id === data.id
        })
        if (index > -1) {
            state.accountDetails.ownedExtracurriculums.splice(index,1,data)
        }
    },
    REMOVE_EXTRACURRICULUM(state,courseId){
        let index = state.accountDetails.extracurriculums.findIndex(c=>{
            return c.id == courseId
        })
        if (index > -1) {
            state.accountDetails.extracurriculums.splice(index,1)
        }
    },
    REMOVE_OWNED_EXTRACURRICULUM(state,courseId){
        let index = state.accountDetails.ownedExtracurriculums.findIndex(c=>{
            return c.id == courseId
        })
        if (index > -1) {
            state.accountDetails.ownedExtracurriculums.splice(index,1)
        }
    },
    //courses
    ADD_NEW_COURSE(state,data){
        state.accountDetails.courses.unshift(data)
    },
    ADD_NEW_OWNED_COURSE(state,data){
        state.accountDetails.ownedCourses.unshift(data)
    },
    UPDATE_COURSE(state,data){
        let index = state.accountDetails.courses.findIndex(c=>{
            return c.id === data.id
        })
        if (index > -1) {
            state.accountDetails.courses.splice(index,1,data)
        }
    },
    UPDATE_OWNED_COURSE(state,data){
        let index = state.accountDetails.ownedCourses.findIndex(c=>{
            return c.id === data.id
        })
        if (index > -1) {
            state.accountDetails.ownedCourses.splice(index,1,data)
        }
    },
    REMOVE_COURSE(state,courseId){
        let index = state.accountDetails.courses.findIndex(c=>{
            return c.id == courseId
        })
        if (index > -1) {
            state.accountDetails.courses.splice(index,1)
        }
    },
    REMOVE_OWNED_COURSE(state,courseId){
        let index = state.accountDetails.ownedCourses.findIndex(c=>{
            return c.id == courseId
        })
        if (index > -1) {
            state.accountDetails.ownedCourses.splice(index,1)
        }
    },
    ADD_NEW_ASSESSMENT(state,data){
        state.accountDetails.assessments.unshift(data)
    },
    UPDATE_ASSESSMENT(state,data){
        let index = state.accountDetails.assessments.findIndex(assessment=>{
            return assessment.id === data.id
        })
        if (index === -1) {
            return
        }
        state.accountDetails.assessments.splice(index,1,data)
    },
    REMOVE_ASSESSMENT(state,assessmentId){
        let index = state.accountDetails.assessments.findIndex(assessment=>{
            return assessment.id == assessmentId
        })
        if (index === -1) {
            return
        }
        state.accountDetails.assessments.splice(index,1)
    },
    //programs
    ADD_NEW_PROGRAM(state,data){
        state.accountDetails.programs.unshift(data)
    },
    ADD_NEW_OWNED_PROGRAM(state,data){
        state.accountDetails.ownedPrograms.unshift(data)
    },
    UPDATE_PROGRAM(state,data){
        let index = state.accountDetails.programs.findIndex(c=>{
            return c.id === data.id
        })
        if (index > -1) {
            state.accountDetails.programs.splice(index,1,data)
        }
    },
    UPDATE_OWNED_PROGRAM(state,data){
        let index = state.accountDetails.ownedPrograms.findIndex(c=>{
            return c.id === data.id
        })
        if (index > -1) {
            state.accountDetails.ownedPrograms.splice(index,1,data)
        }
    },
    REMOVE_PROGRAM(state,programId){
        let index = state.accountDetails.programs.findIndex(c=>{
            return c.id == programId
        })
        if (index > -1) {
            state.accountDetails.programs.splice(index,1)
        }
    },
    REMOVE_OWNED_PROGRAM(state,programId){
        let index = state.accountDetails.ownedPrograms.findIndex(c=>{
            return c.id == programId
        })
        if (index > -1) {
            state.accountDetails.ownedPrograms.splice(index,1)
        }
    },
    //programs
    ADD_NEW_LESSON_TO_LESSONS(state,data){
        if (state.accountDetails.account === 'facilitator' ||
            state.accountDetails.account === 'professional') {
            state.accountDetails.lessons.unshift(data.lesson)
        }
    },
    ADD_NEW_LESSON_TO_ITEMS(state,data){
        let index
        data.items.forEach(item=>{ //items are the courses and subjects with classes
            //for facilitating 
            //for onwership
            if (item.type === 'course') {
                index = state.accountDetails.courses.findIndex(course=>{
                    return course.id === item.id
                })
                if (index > -1 &&
                    state.accountDetails.courses[index].hasOwnProperty('lessons')) {
                    state.accountDetails.courses[index].lessons += 1
                }
                if (data.owner) {
                    index = state.accountDetails.ownedCourses.findIndex(course=>{
                        return course.id === item.id
                    })
                    if (index > -1 &&
                        state.accountDetails.ownedCourses[index].hasOwnProperty('lessons')) {
                        state.accountDetails.ownedCourses[index].lessons += 1
                    }
                }
            } else if (item.type === 'class') {
                index = state.accountDetails.classes.findIndex(cl=>{
                    return cl.id === item.id
                })
                if (index > -1 &&
                    state.accountDetails.classes[index].hasOwnProperty('lessons')) {
                    state.accountDetails.classes[index].lessons += 1
                }
                if (data.owner) {
                    index = state.accountDetails.ownedClasses.findIndex(cl=>{
                        return cl.id === item.id
                    })
                    if (index > -1 &&
                        state.accountDetails.ownedClasses[index].hasOwnProperty('lessons')) {
                        state.accountDetails.ownedClasses[index].lessons += 1
                    }
                }
            } else if (item.type === 'subject' && item.classId) {
                index = state.accountDetails.classes.findIndex(cl=>{
                    return cl.id === item.classId
                })
                if (index > -1 &&
                    state.accountDetails.classes[index].hasOwnProperty('lessons')) {
                    state.accountDetails.classes[index].lessons += 1
                }
                if (data.owner) {
                    index = state.accountDetails.ownedClasses.findIndex(cl=>{
                        return cl.id === item.classId
                    })
                    if (index > -1 &&
                        state.accountDetails.ownedClasses[index].hasOwnProperty('lessons')) {
                        state.accountDetails.ownedClasses[index].lessons += 1
                    }
                }
            } else if (item.type === 'courseSection' && item.courseId) {
                index = state.accountDetails.classes.findIndex(cl=>{
                    return cl.id === item.courseId
                })
                if (index > -1 &&
                    state.accountDetails.courses[index].hasOwnProperty('lessons')) {
                    state.accountDetails.courses[index].lessons += 1
                }
                if (data.owner) {
                    index = state.accountDetails.ownedClasses.findIndex(cl=>{
                        return cl.id === item.courseId
                    })
                    if (index > -1 &&
                        state.accountDetails.ownedCourses[index].hasOwnProperty('lessons')) {
                        state.accountDetails.ownedCourses[index].lessons += 1
                    }
                }
            } else if (item.type === 'extracurriculum') {
                index = state.accountDetails.extracurriculums.findIndex(extracurriculum=>{
                    return extracurriculum.id === item.id
                })
                if (index > -1 &&
                    state.accountDetails.extracurriculums[index].hasOwnProperty('lessons')) {
                    state.accountDetails.extracurriculums[index].lessons += 1
                }
                if (data.owner) {
                    index = state.accountDetails.extracurriculums.findIndex(extracurriculum=>{
                        return extracurriculum.id === item.id
                    })
                    if (index > -1 &&
                        state.accountDetails.extracurriculums[index].hasOwnProperty('lessons')) {
                        state.accountDetails.extracurriculums[index].lessons += 1
                    }
                }
            }
        })
    },
    UPDATE_LESSON(state,data){
        // let index = state.accountDetails.programs.findIndex(c=>{
        //     return c.id === data.id
        // })
        // if (index > -1) {
        //     state.accountDetails.programs.splice(index,1,data)
        // }
    },
    UPDATE_ACCOUNT_LESSON(state,data){
        if (state.accountDetails.account === 'facilitator' ||
            state.accountDetails.account === 'professional') {
            let index = state.accountDetails.lessons.findIndex(lesson=>{
                return data.id === lesson.id
            })
            if (index > -1) {
                state.accountDetails.lessons.splice(index,1,data)
            }
        }
    },
    REMOVE_LESSON(state,lessonId){
        let index = state.accountDetails.lessons.findIndex(c=>{
            return c.id == lessonId
        })
        if (index > -1) {
            state.accountDetails.lessons.splice(index,1)
        }
    },
    REMOVE_ACCOUNT_LESSON(state,data){
        if (state.accountDetails.account === 'facilitator' ||
            state.accountDetails.account === 'professional') {
            let index = state.accountDetails.lessons.findIndex(lesson=>{
                return lesson.id === data.lessonId
            })
            if (index > -1) {
                state.accountDetails.lessons.splice(index,1)
            }
        }
        data.items.forEach(item=>{
            if (item.type === 'course') {
                index = state.accountDetails.courses.findIndex(course=>{
                    return course.id === item.id
                })
                if (index > -1 &&
                    state.accountDetails.courses[index].hasOwnProperty('lessons')) {
                    state.accountDetails.courses[index].lessons -= 1
                }
                index = state.accountDetails.ownedCourses.findIndex(course=>{
                    return course.id === item.id
                })
                if (index > -1 &&
                    state.accountDetails.ownedCourses[index].hasOwnProperty('lessons')) {
                    state.accountDetails.ownedCourses[index].lessons -= 1
                }
            } else if (item.type === 'class') {
                index = state.accountDetails.classes.findIndex(cl=>{
                    return cl.id === item.id
                })
                if (index > -1 &&
                    state.accountDetails.classes[index].hasOwnProperty('lessons')) {
                    state.accountDetails.classes[index].lessons -= 1
                }
                index = state.accountDetails.ownedClasses.findIndex(cl=>{
                    return cl.id === item.id
                })
                if (index > -1 &&
                    state.accountDetails.ownedClasses[index].hasOwnProperty('lessons')) {
                    state.accountDetails.ownedClasses[index].lessons -= 1
                }
            } else if (item.type === 'subject' && item.classId) {
                index = state.accountDetails.classes.findIndex(cl=>{
                    return cl.id === item.classId
                })
                if (index > -1 &&
                    state.accountDetails.classes[index].hasOwnProperty('lessons')) {
                    state.accountDetails.classes[index].lessons -= 1
                }
                index = state.accountDetails.ownedClasses.findIndex(cl=>{
                    return cl.id === item.classId
                })
                if (index > -1 &&
                    state.accountDetails.ownedClasses[index].hasOwnProperty('lessons')) {
                    state.accountDetails.ownedClasses[index].lessons -= 1
                }
            } else if (item.type === 'courseSection' && item.courseId) {
                index = state.accountDetails.classes.findIndex(cl=>{
                    return cl.id === item.courseId
                })
                if (index > -1 &&
                    state.accountDetails.courses[index].hasOwnProperty('lessons')) {
                    state.accountDetails.courses[index].lessons -= 1
                }
                index = state.accountDetails.ownedClasses.findIndex(cl=>{
                    return cl.id === item.courseId
                })
                if (index > -1 &&
                    state.accountDetails.ownedCourses[index].hasOwnProperty('lessons')) {
                    state.accountDetails.ownedCourses[index].lessons -= 1
                }
            } else if (item.type === 'extracurriculum') {
                index = state.accountDetails.extracurriculums.findIndex(extracurriculum=>{
                    return extracurriculum.id === item.id
                })
                if (index > -1 &&
                    state.accountDetails.extracurriculums[index].hasOwnProperty('lessons')) {
                    state.accountDetails.extracurriculums[index].lessons -= 1
                }
                index = state.accountDetails.extracurriculums.findIndex(extracurriculum=>{
                    return extracurriculum.id === item.id
                })
                if (index > -1 &&
                    state.accountDetails.extracurriculums[index].hasOwnProperty('lessons')) {
                    state.accountDetails.extracurriculums[index].lessons -= 1
                }
            }
        })
    },
    //collaboration
    ADD_COLLABORATION(state, collaboration) {
        if (state.accountDetails.hasOwnProperty('collaborations')) {
            state.accountDetails.collaborations.unshift(collaboration)
        }
    },
    UPDATE_COLLABORATION(state, collaboration) {
        if (state.accountDetails.hasOwnProperty('collaborations')) {
            let index = state.accountDetails.collaborations.findIndex(collabo=>{
                return collabo.id === collaboration.id
            })
            if (index > -1) {
                state.accountDetails.collaborations.splice(index,1,collaboration)
            }
        }
    },
    REMOVE_COLLABORATION(state, collaborationId) {
        if (state.accountDetails.hasOwnProperty('collaborations')) {
            let index = state.accountDetails.collaborations.findIndex(collabo=>{
                return collabo.id === collaborationId
            })
            if (index > -1) {
                state.accountDetails.collaborations.splice(index,1)
            }
        }
    },
    ///
    SCHOOL_ADD_ACCOUNTS(state,data){
        if (data.accountOne.account === 'learner') {
            state.accountDetails.learners.push(data.accountOne)
        } else if (data.accountOne.account === 'parent') {
            state.accountDetails.parents.push(data.accountOne)
        }

        if (data.accountTwo) {
            state.accountDetails.parents.push(data.accountTwo)
        }
    },
    ADD_ACCOUNT_DETAILS(state,data){
        if (data.account === state.accountDetails.account && 
            data.accountId === state.accountDetails.accountId) {
            if (data.what === 'admin') {
                state.accountDetails.admins.push(data.data)
            } else if (data.what === 'facilitator') {
                state.accountDetails.facilitators.push(data.data)
            } else if (data.what === 'school') {
                state.accountDetails.schools.push(data.data)
            } else if (data.what === 'academicYear') {
                state.accountDetails.academicYears.push(data.data)
            }
        }
    },
    //comments
    COMMENT_SUCCESS(state, data){
        let commentIndex 
        if (!data.comment.commentable_type.toLocaleLowerCase().includes('comment')) {
            state.mainSectionComments.unshift(data.comment)
        } else if (data.comment.commentable_type.toLocaleLowerCase().includes('comment')) {
            commentIndex = state.mainSectionComments.findIndex(comment=>{
                return comment.id === data.comment.commentable_id
            })
            if (commentIndex > -1) {
                state.mainSectionComments[commentIndex].comments += 1
            }
        }
    },
    COMMENT_UPDATE_SUCCESS(state,data){
        let commentIndex
        if (!data.comment.commentable_type.toLocaleLowerCase().includes('comment')) {
            commentIndex = state.mainSectionComments.findIndex(comment=>{
                return comment.id === data.comment.id
            })
            if (commentIndex > -1) {
                state.mainSectionComments.splice(commentIndex,1,data.comment)
            }
        }
    },
    COMMENT_DELETE_SUCCESS(state,data){
        let commentIndex
        if (!data.owner.toLocaleLowerCase().includes('comment')) {
            commentIndex = state.mainSectionComments.findIndex(comment=>{
                return comment.id === data.commentId
            })
            if (commentIndex > -1) {
                state.mainSectionComments.splice(commentIndex,1)
            }
        } else if (data.owner.toLocaleLowerCase().includes('comment')) {
            commentIndex = state.mainSectionComments.findIndex(comment=>{
                return comment.id === data.ownerId
            })
            if (commentIndex > -1) {
                state.mainSectionComments[commentIndex].comments -= 1
            }
        }
    },
    GET_COMMENTS_SUCCESS(state,data){
        if (data.nextPage === 1) {
            state.mainSectionComments = data.comments
        } else {
            state.mainSectionComments.push(...data.comments)
        }
    },
    NEW_COMMENT(state, data){ //only for posts
        let itemId = Number(data.itemId),
            index
        if (data.item === 'comment') {
            index = state.mainSectionComments.findIndex(comment=>{
                return comment.id === itemId
            })
            if (index > -1) {
                state.mainSectionComments[index].comments += 1
            }
        } else if (data.item !== 'comment') {
            state.mainSectionComments.unshift(data.comment)
        }
    },
    UPDATE_COMMENT(state, data){
        let itemId = Number(data.itemId),
            commentIndex
        if (data.item === 'comment') {
            
        } else if (data.item !== 'comment') {
            commentIndex = state.mainSectionComments.findIndex(comment=>{
                return comment.id === itemId
            })
            if (commentIndex > -1) {
                state.mainSectionComments.splice(commentIndex,1,data.comment)
            }
        }
    },
    REMOVE_COMMENT(state, data){
        let itemId = Number(data.itemId),
            commentId = Number(data.commentId),
            commentIndex = null
        if (data.item === 'comment') {
            commentIndex = state.mainSectionComments.findIndex(comment=>{
                return comment.id === commentId
            })
            if (commentIndex > -1) {
                state.mainSectionComments[commentIndex] -= 1
            }
        } else if (data.item !== 'comment') {
            commentIndex = state.mainSectionComments.findIndex(comment=>{
                return comment.id === commentId
            })
            if (commentIndex > -1) {
                state.mainSectionComments.splice(commentIndex,1)
            }
        }
    },
    //saves
    SAVE_CREATE_SUCCESS(state,data){

    },
    SAVE_DELETE_SUCCESS(state,data){

    },
    //flags
    FLAG_DELETE_SUCCESS(state,data){

    },
    FLAG_CREATE_SUCCESS(state,data){

    },
    //attachments
    ATTACHMENT_DELETE_SUCCESS(state,data){

    },
    ATTACHMENT_CREATE_SUCCESS(state,data){

    },
    //likes
    LIKE_DELETE_SUCCESS(state,data){
        let likeIndex
        if (data.item === 'comment') {
            let commentIndex = state.mainSectionComments.findIndex(comment=>{
                return comment.id === data.itemId
            })
            if (commentIndex > -1) {
                likeIndex = state.mainSectionComments[commentIndex].likes.findIndex(like=>{
                    return like.id === data.likeId
                })

                if (likeIndex > -1) {
                    state.mainSectionComments[commentIndex].likes.splice(likeIndex,1)
                    return
                }
            }
        }
    },
    LIKE_CREATE_SUCCESS(state,data){
        let likeIndex
        if (data.item === 'comment') {
            let commentIndex = state.mainSectionComments.findIndex(comment=>{
                return comment.id === data.itemId
            })

            if (commentIndex > -1) {
                likeIndex = state.mainSectionComments[commentIndex].likes.findIndex(like=>{
                    return like.id === data.like.id
                })
                if (likeIndex > -1) {
                    return
                }
                state.mainSectionComments[commentIndex].likes.push(data.like)
            }
        } 
    },
    NEW_LIKE(state,data){
        let index = null,
            likeIndex = null,
            itemId = Number(data.itemId)
        if (data.item === 'comment') {
            index = state.mainSectionComments.findIndex(comment=>{
                return comment.id === itemId
            })
            if (index > -1) {
                likeIndex = state.mainSectionComments[index].likes.findIndex(l=>{
                    return l.id === data.like.id
                })
                if (likeIndex === -1) {
                    state.mainSectionComments[index].likes.push(data.like)
                }
            }
        }
    },
    REMOVE_LIKE(state,data){
        let index = null,
            likeIndex = null,
            itemId = Number(data.itemId),
            likeId = Number(data.likeId)
        if (data.item === 'comment') {
            index = state.mainSectionComments.findIndex(comment=>{
                return comment.id === itemId
            })
            if (index > -1) {
                likeIndex = state.mainSectionComments[index].likes.findIndex(l=>{
                    return l.id === likeId
                })
                if (likeIndex > -1) {
                    state.mainSectionComments[index].likes.splice(likeIndex,1)
                }
            }
        }
    },
    //account attachmennts
    ADD_ACCOUNT_ATTACHMENT(state,data){
        data.forEach(attachment=>{
            if (state.accountDetails.hasOwnProperty(attachment.type)) {
                state.accountDetails[attachment.type].push(attachment)
            }
        })
    },
    REMOVE_ACCOUNT_ATTACHMENT(state,data){ //fpr attahcments and accounts 
        let accountAttachment = `${data.item}s`,
            index
        if (state.accountDetails.hasOwnProperty(accountAttachment)) {
            if (data.item === 'course' || data.item === 'grade' ||
                data.item === 'subject' || data.item === 'program') {
                index = state.accountDetails[accountAttachment].findIndex(attachment=>{
                    return attachment.data.id === data.itemId
                })
            } else if (data.item === 'professioal' || data.item === 'parent' ||
                data.item === 'learner' || data.item === 'facilitator' || 
                data.item === 'admin') {
                index = state.accountDetails[accountAttachment].findIndex(account=>{
                    return account.accountId === data.itemId
                })
            }
        }
        if (index > -1) {
            state.accountDetails[accountAttachment].splice(index,1)
        }
    },

}

export default mutations