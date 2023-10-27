<template>
<div class="w-[300px] bg-sky-950 rounded-lg shadow-lg">
    <div class="p-4">
        <div  class="flex items-center justify-between">
            <h2 style="display: flex;" class="text-lg text-zinc-100 font-black mb-3">{{ kanban.phases[props.phase_id].name }}
            
             <span class="cardcount " v-if="props.phase_id===1">{{cardCount.backlog}}</span>
             <span class="cardcount" v-if="props.phase_id===2">{{cardCount.todo}}</span>
             <span class="cardcount" v-if="props.phase_id===3">{{cardCount.doing}}</span>
             <span class="cardcount" v-if="props.phase_id===4">{{cardCount.done}}</span>
             <span class="cardcount" v-if="props.phase_id===5">{{cardCount.archieved}}</span>
             <span class="cardcount" v-if="props.phase_id===6">{{cardCount.completion}}</span>
            </h2>
            <PlusIcon 
                @click="createTask()" 
                class="mb-3 h-6 w-6 text-white hover:cursor-pointer" 
                aria-hidden="true" />
        </div >
        <task-card v-if="kanban.phases[props.phase_id].tasks.length > 0"  v-for="task in kanban.phases[props.phase_id].tasks" :task="task" />
        
        <!-- A card to create a new task -->
        <div class="w-full flex items-center justify-between bg-white text-gray-900 hover:cursor-pointer shadow-md rounded-lg p-3 relative"
            @click="createTask()">
            <span>Create a new task</span>
            <PlusIcon class="h-6 w-6" aria-hidden="true" />
        </div>

    </div>
</div>
</template>

<script setup>
// get the props


import {ref, onMounted, onUpdated} from 'vue'
import { useKanbanStore } from '../stores/kanban'
import { PlusIcon } from '@heroicons/vue/20/solid'
const kanban = useKanbanStore()
const cardCount = ref({});

const props = defineProps({
    phase_id: {
        type: Number,
        required: true
    }, 
}) 

const createTask = function () {
    kanban.creatingTask = true;
    kanban.creatingTaskProps.phase_id = props.phase_id;
}
const updateCard = async () => {
    const response = await axios.get('/count'); 
    cardCount.value = response.data;
};

onMounted(() => {
    updateCard();
}); 
onUpdated(() => { 
    updateCard();
}); 

</script>
<style scoped>
.cardcount{
    display: flex;
    height: 30px !important;
    width: 30px !important;
    border: 2px solid black;
    border-radius: 50%;
    background-color: #ffffff;
    color: #082F49;
    margin-left: 5px;
    flex-direction: row;
    justify-content: center;
}
</style>