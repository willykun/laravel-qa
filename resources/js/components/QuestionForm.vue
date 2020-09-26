<template>
    <form @submit.prevent="handleSubmit">
        <div class="form-group">
            <label for="question-title">Question title</label>
            <input type="text" name="title" v-model="title" :class="errorClass('title')">
           
            <div v-if="errors['title'][0]" class="invalid-feedback">
                <strong>{{ errors['title'][0] }}</strong>
            </div>
        </div>
        <div class="form-group">
            <label for="question-body">Explain you question</label>
            <textarea name="body" rows="10" :class="errorClass('body')" v-model="body"></textarea>
            <div v-if="errors['body'][0]" class="invalid-feedback">
                <strong>{{ errors['body'][0] }}</strong>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-outline-primary btn-lg">{{ buttonText }}</button>
        </div>
    </form>
</template>

<script>
import EventBus from '../event-bus'

export default {
    data(){
        return{
            title: '',
            body: '',
            errors: {
                title: [],
                body: []
            }
        }
    },

    mounted(){ //the best place to listen to the error event is in mounted hook
       EventBus.$on('error', errors => this.errors = errors)
    },

    methods:{
        handleSubmit(){
            this.$emit('submitted', {
                title: this.title,
                body: this.body
            })
        },

        errorClass(column){
            return [
                'form-control',
                this.errors[column] && this.errors[column][0] ? 'is-invalid' : ''
            ]
        }
    },

    computed:{
        buttonText(){
            return 'Ask Question'
        }
    }
}
</script>