<template>
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">
                {{ liga }}
            </h1>
            <p class="lead">List of matches by {{ liga }}</p>
            <b-button variant="primary" to="/">Back</b-button>
        </div>
    </div>
</template>
<script>
import { defineComponent, toRef } from '@vue/composition-api'

export default defineComponent({
    props: {
        liga: String
    },
    setup(props) {
        const liga = toRef(props, 'title')
    },
})
</script>
