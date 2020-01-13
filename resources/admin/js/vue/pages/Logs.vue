<template>
    <div class="container-fluid" id="page-logs">
        <div class="bmd-btn-fab-group">
            <button class="btn btn-danger bmd-btn-fab tooltip-toggle mb-3" @click="clearLogs" title="Tabel legen">
                <i class="fal fa-fw fa-trash-alt"></i>
            </button>

            <button class="btn btn-success bmd-btn-fab tooltip-toggle" @click="getLogs" title="Tabel verversen">
                <i class="fal fa-fw fa-sync"></i>
            </button>
        </div>

        <div class="row mb-3">
            <div class="col-12 col-sm-10 offset-sm-1">
                <div class="card card-2">
                    <div class="card-body">
                        <h3>
                            <i class="fal fa-fw fa-list"></i> Applicatie logs
                        </h3>

                        <hr/>

                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th class="text-center">Level</th>
                                <th>Bericht</th>
                                <th>Tijd</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="log in logs" @click="$emit('click', log)">
                                <td>{{ log.id }}</td>
                                <td class="text-center"><span class="badge font-weight-normal" :class="getBadgeClass(log.level)">{{ log.level_name }}</span>
                                </td>
                                <td class="w-75">{{ log.message }}</td>
                                <td>{{ log.logged_at }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .bmd-btn-fab-group {
        position: fixed !important;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
    }
</style>

<script>
    export default {
        data() {
            return {
                logs: []
            }
        },
        methods: {
            getLogs() {
                this.$http.get(route('admin.api.logs'))
                    .then(response => {
                        this.logs = response.data.logs;
                    })
                    .catch(this.handleAxiosError);
            },
            getBadgeClass(level) {
                return {
                    'badge-light': level === 100, // Debug
                    'badge-info': level === 200, // Info
                    'badge-secondary': level === 250, // Notice
                    'badge-warning': level === 300, // Warning
                    'badge-danger': level === 400 || level === 500, // Error or Critical
                    'badge-dark': level === 550 || level === 600, // Alert or Emergency
                };
            },
            clearLogs() {
                this.$http.delete(route('admin.api.delete-logs'))
                    .then(response => {
                        this.$root.$emit('send-notify', {
                            text: response.data.message,
                            success: response.data.success
                        });

                        this.getLogs();
                    })
                    .catch(this.handleAxiosError);
            },
            handleAxiosError(error) {
                console.error(error);

                this.$root.$emit('send-notify', {
                    text: error,
                    success: false
                });
            }
        },
        mounted() {
            this.getLogs();
        }
    }
</script>
