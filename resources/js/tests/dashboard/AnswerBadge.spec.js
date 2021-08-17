import {mount} from '@vue/test-utils'
import AnswerBadge from '../../components/dashboard/AnswerBadge.vue'
import PossibleAnswerBadge from '../../components/dashboard/PossibleAnswerBadge.vue'

let wrapper = null

beforeEach(() => {
    
    wrapper = mount(AnswerBadge, {
        stubs: {
            'font-awesome-icon': {
                'template': '<div>hey</div>'
            }
        }
    })
});

afterEach(() => {
    wrapper?.destroy()
});

describe('AnswerBadge.vue', () => {
    test('renders answer details', async () => {
        
        await wrapper.setProps({
            answer: {"id":19,"question":{"id":22,"body":"what is your favorite colour","questionId":13,"state":null,"scoreOver":2,"position":1,"hint":"rainbow","answerType":"SHORT_ANSWER","publishedAt":"2021-05-25T08:16:41.000000Z","updated_at":"2021-07-05T05:21:51.000000Z","correctPossibleAnswers":null,"possibleAnswers":[],"answers_number":1,"answers":{},"images":null,"videos":null,"audios":null,"files":null},"possibleAnswerIds":null,"assessmentSectionId":13,"answer":"red","scoreOver":2,"isMarker":false,"avgScore":null,"maxScore":null,"minScore":null,"images":[],"videos":[],"audios":[],"files":[],"createdAt":"6 days ago"}
        })
        
        const answer = wrapper.find("[data-testid='answer']")

        console.log('answer :>> ', answer);
        expect(answer.text()).toBe('good')
    });
    
    test('renders possible answer badge', async () => {
        
        await wrapper.setProps({
            answer: {
                "id": 22,
                "question": {
                    "id": 25, 
                    "body": "what is your sex?", 
                    "questionId": 14, 
                    "state": null, 
                    "scoreOver": 2, 
                    "position": 2, 
                    "hint": "gender", 
                    "answerType": "OPTION", 
                    "publishedAt": "2021-05-25T08:16:41.000000Z", 
                    "updated_at": "2021-07-05T05:21:51.000000Z", 
                    "correctPossibleAnswers": null, 
                    "possibleAnswers": [
                        { "id": 28, "option": "male", "position": 1, "state": null },
                        { "id": 29, "option": "female", "position": 2, "state": null }
                    ], 
                    "answers_number": 1, 
                    "answers": {}, 
                    "images": null, 
                    "videos": null, 
                    "audios": null, 
                    "files": null
                },
                "possibleAnswerIds": [28],
                "assessmentSectionId": 14,
                "answer": null, 
                "scoreOver": 2, 
                "isMarker": false, 
                "avgScore": null, 
                "maxScore": null, 
                "minScore": null, 
                "images": [], 
                "videos": [], 
                "audios": [], 
                "files": [], 
                "createdAt": "6 days ago"
            }
        })
        
        const answer = wrapper.find("[data-testid='answer']")

        expect(answer.element).toBeNull()

        const badge = wrapper.findComponent(PossibleAnswerBadge)
        expect(badge.element).toBeTruthy()
    });
});