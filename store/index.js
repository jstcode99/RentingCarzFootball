export const state = () => ({
    ligas: [],
    competitions: [],
    matches: [],
    selectedLigue: '',
})
  
export const mutations = {
    setLigas(state, ligas) {
      state.ligas = ligas
    },
    setCompetitions(state, competitions) {
        state.competitions = competitions
    },
    setMatches(state, matches) {
        state.matches = matches
    },
    setSelectedLigue(state, selectedLigue) {
        state.selectedLigue = selectedLigue
    }
}
// export const getters = {
//     getLigas(state) {
//       return state.ligas
//     },
// }