<template>
    <div>
        <b-skeleton-wrapper :loading="loading" class="mt-3">
            <template #loading>
                <b-container>
                    <b-card bg-variant="dark">
                        <b-skeleton width="85%"></b-skeleton>
                        <b-skeleton width="55%"></b-skeleton>
                    </b-card>
                </b-container>
            </template>
            <banner :liga="liga"/>
        </b-skeleton-wrapper>
        <b-container>
            <b-skeleton-wrapper :loading="loading" class="skeleton-table-matches mt-3">
                <template #loading>
                    <b-skeleton-table
                        table-variant="dark"
                        :bordered="false"
                        :borderless="false"
                        :no-border-collapse="false"
                        variant="dark"
                        :rows="5"
                        :columns="4"
                        :table-props="{ striped: true }"
                    />
                </template>
                <b-row class="mt-3">
                    <b-col md="12" sm="12">
                        <b-row>
                            <b-col md="6" sm="6">
                                <div class="text-light mt-2">
                                    Sorting By: <b class="text-uppercase">{{ sortBy }}</b>, Sort Direction:
                                    <b class="text-uppercase">{{ sortDesc ? 'Descending' : 'Ascending' }}</b>
                                </div>
                            </b-col>
                            <b-col md="6" sm="6">
                                <b-form-group
                                    label="Per page"
                                    label-for="per-page-select"
                                    label-cols-sm="3"
                                    label-cols-md="3"
                                    label-cols-lg="3"
                                    label-align-sm="right"
                                    label-size="sm"
                                    label-class="text-light"
                                    class="mb-0 mt-2"
                                >
                                    <b-form-select
                                        id="per-page-select"
                                        v-model="perPage"
                                        :options="pageOptions"
                                        size="sm"
                                        class="bg-dark text-light"
                                    ></b-form-select>
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </b-col>
                    <b-col md="12" sm="12">
                        <b-table
                            class="pt-2"
                            striped
                            responsive="sm"
                            dark
                            hover
                            :fields="fields"
                            :items="matches"
                            :sort-by.sync="sortBy"
                            :sort-desc.sync="sortDesc"
                            :current-page="currentPage"
                            :per-page="perPage"
                            sort-icon-left
                            tbody-tr-class="cursor-pointer"
                        >
                            <template #cell(name)="data">
                                <div class="d-inline align-items-center">
                                    <span class="mr-auto">
                                        {{ data.item.homeTeam.name }} - 
                                        <b>{{ getGoals('homeTeam', data.item.score) }}</b>
                                    </span>
                                    <br>
                                    <span class="mr-auto">
                                        {{ data.item.awayTeam.name }} - 
                                        <b>{{ getGoals('awayTeam', data.item.score) }}</b>
                                    </span>
                                </div>
                            </template>
                            <template #cell(score)="data">
                                <div class="d-inline align-items-center">
                                    {{ getWinner(data.item.score.winner, data.item)  }}
                                </div>
                            </template>
                        </b-table>
                    </b-col>
                    <b-col sm="12" md="12" class="my-1">
                        <div class="d-flex justify-content-center">
                            <b-pagination
                                v-model="currentPage"
                                :total-rows="totalRows"
                                :per-page="perPage"
                                pills
                                align="fill"
                                size="md"
                                class="my-0"
                                last-class="bg-dark text-light"
                            />
                        </div>
                    </b-col>
                </b-row>
            </b-skeleton-wrapper>
        </b-container>
    </div>
</template>

<script>
import { defineComponent, useRoute, useStore, useFetch, computed, ref } from '@nuxtjs/composition-api'

export default defineComponent({
    setup() {
        const route = useRoute()
        const idLeague = computed(() => route.value.params.id)
        const currentPage = ref(1)
        const perPage = ref(10)
        const pageOptions = ref([10, 15, 50, { value: 100, text: "Show a lot" }])
        const sortBy = ref('name')
        const sortDesc = ref(false)
        const loading = ref(null)
        const fields = [
            { key: 'name', label: 'Teams', sortable: false },
            { key: 'status', label: 'Status', sortable: true },
            {
                key: 'utcDate',
                label: 'Date match',
                formatter: (value, key, item) => {
                    return new Date(item.utcDate).toDateString()
                },
                sortable: true
            },
            {
                key: 'score',
                label: 'Winner',
                sortable: true
            }
        ]
        const store = useStore()

        const getGoals = (by, scores) => {
            let total = 0
            if(scores) {
                if(by === 'homeTeam') {
                    total += scores.extraTime.homeTeam ? parseInt(scores.extraTime.homeTeam) : 0
                    total += scores.fullTime.homeTeam ? parseInt(scores.fullTime.homeTeam) : 0
                    total += scores.halfTime.homeTeam ? parseInt(scores.halfTime.homeTeam) : 0
                    total += scores.penalties.homeTeam ? parseInt(scores.penalties.homeTeam) : 0

                } else {
                    total += scores.extraTime.homeTeam ? parseInt(scores.extraTime.awayTeam) : 0
                    total += scores.fullTime.awayTeam ? parseInt(scores.fullTime.awayTeam) : 0
                    total += scores.halfTime.awayTeam ? parseInt(scores.halfTime.awayTeam) : 0
                    total += scores.penalties.awayTeam ? parseInt(scores.penalties.awayTeam) : 0
                }
            }
            return total
        }

        const getWinner = (winner, item) => {
            let winnerTeam = ''
            if(winner === 'HOME_TEAM') {
                winnerTeam = item.homeTeam.name
            }
            if(winner === 'DRAW') {
                winnerTeam = item.awayTeam.name
            }
            if(winner === 'TIE') {
                winnerTeam = 'TIE'
            }
            return winnerTeam
        }

        const { fetch, fetchState } = useFetch(async ({ $axios, $root }) => {
            if(!store.state.matches.length) {
                loading.value = true
                // For default 2077 because value to EUR
                let currentDate = new Date()
                let lastDateOnCurrent = new Date(currentDate.getFullYear(), 11, 31)
                let filters = {
                    dateFrom : currentDate.toISOString().slice(0, 10),
                    dateTo : lastDateOnCurrent.toISOString().slice(0, 10),
                    stage : '',
                    status : '',
                    matchday : '',
                    group : ''
                }
                filters = JSON.stringify(filters)
                let response = await $axios.$get(`/get-league-matches/${idLeague.value}/filters/${filters}`).catch(error => {
                    $root.$bvToast.toast('Request canceled', {
                        title: 'Warning',
                        toaster: 'b-toaster-bottom-right',
                        autoHideDelay: 5000,
                        appendToast: true,
                        variant: 'warning',
                        solid: true
                    })
                    loading.value = false
                    console.error('Request canceled', error.message)
                })
                if(response) {
                    loading.value = false
                    // order by date season
                    let matches = response.matches.sort((a ,b) => new Date(b.utcDate) - new Date(a.utcDate))
                    store.commit('setSelectedLigue', response.competition.name)
                    store.commit('setMatches', matches)
                }
            }
        })

        // Manually trigger a refetch
        fetch()

        // Access fetch error, pending and timestamp
        fetchState

        return {
            getGoals,
            getWinner,
            totalRows: computed(() => store.state.matches.length),
            currentPage,
            perPage,
            pageOptions,
            sortBy,
            sortDesc,
            loading,
            fields,
            matches: computed(() => store.state.matches),
            liga: computed(() => store.state.selectedLigue),
        }
    },
})
</script>
<style>
.skeleton-table-matches, .table, thead, th {
    border-color: #252525 !important;
}

.skeleton-table-matches, .table, tbody, td {
    border-color: #252525 !important;
}
.cursor-pointer {
    cursor: pointer;
}
</style>