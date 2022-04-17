<template>
    <b-container>
        <b-skeleton-wrapper :loading="loading" class="skeleton-table-ligas mt-3">
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
                                />
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
                        :items="ligas"
                        :sort-by.sync="sortBy"
                        :sort-desc.sync="sortDesc"
                        :current-page="currentPage"
                        :per-page="perPage"
                        sort-icon-left
                        tbody-tr-class="cursor-pointer"
                        @row-clicked="leagueClickHandler"
                    >
                        <template #cell(emblemUrl)="data">
                            <div class="d-flex align-items-center">
                                <b-avatar
                                    v-if="data.value"
                                    class="mr-3"
                                    variant="light"
                                    :src="data.value"
                                />
                                <span class="mr-auto">
                                    {{ data.item.name }}
                                    <b-badge v-if="isBestLiga(data.item.code)" variant="success"> Best liga </b-badge>
                                </span>
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
</template>

<script>
import { defineComponent, useRouter, useStore, useFetch, computed, ref } from '@nuxtjs/composition-api'
import Banner from '../components/Banner.vue'

export default defineComponent({
  components: { Banner },
    setup() {
        const router = useRouter()
        const currentPage = ref(1)
        const perPage = ref(10)
        const bestLigas = ['CL', 'PD', 'SA', 'BL1', 'PPL', 'FL1', 'DED']
        const pageOptions = ref([10, 15, 50, { value: 100, text: "Show a lot" }])
        const sortBy = ref('name')
        const sortDesc = ref(false)
        const loading = ref(null)
        const fields = [
            { key: 'emblemUrl', label: 'Name', sortable: true },
            { key: 'code', label: 'COD', sortable: true },
            {
                key: 'startDate',
                label: 'Start Date',
                formatter: (value, key, item) => {
                    return new Date(item.currentSeason.startDate).toDateString()
                }
            },
            {
                key: 'winner',
                label: 'Winner',
                formatter: (value, key, item) => {
                    return item.currentSeason.winner ? item.currentSeason.winner.name : 'N/A'
                }
            }
        ]
        const store = useStore()

        const { fetch, fetchState } = useFetch(async ({ $axios, $root }) => {
            if(!store.state.ligas.length) {
                loading.value = true
                // For default 2077 because value to EUR
                let response = await $axios.$get('/get-leagues/2077').catch(error => {
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
                    let ligasWithCode = response.competitions.filter(liga => liga.code ? true : false)
                    // order by date season
                    ligasWithCode = ligasWithCode.sort((a ,b) => new Date(b.currentSeason.startDate) - new Date(a.currentSeason.startDate))
                    store.commit('setLigas', ligasWithCode)
                }
            }
        })

        const leagueClickHandler = (row, index) => {
            router.push(`/competition/${row.code}`)
            store.commit('setSelectedLigue', '')
            store.commit('setMatches', [])
        }

        const isBestLiga = (code) => {
            return bestLigas.find(best => best === code) ? true : false
        }

        // Manually trigger a refetch
        fetch()

        // Access fetch error, pending and timestamp
        fetchState

        return {
            totalRows: computed(() => store.state.ligas.length),
            currentPage,
            perPage,
            pageOptions,
            sortBy,
            sortDesc,
            loading,
            fields,
            bestLigas,
            ligas: computed(() => store.state.ligas),
            leagueClickHandler,
            isBestLiga,
        }
    },
})
</script>
<style>
.skeleton-table-ligas, .table, thead, th {
    border-color: #252525 !important;
}

.skeleton-table-ligas, .table, tbody, td {
    border-color: #252525 !important;
}
.cursor-pointer {
    cursor: pointer;
}
</style>