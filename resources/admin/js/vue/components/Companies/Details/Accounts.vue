<template>
    <div class="card" id="accounts-overview">
        <div class="card-body">
            <h3>
                <i class="fal fa-fw fa-user"></i> Accounts
            </h3>

            <hr />

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Gebruikersnaam</th>
                        <th scope="col" class="d-sm-none d-md-table-cell">E-Mail</th>
                        <th scope="col">Actief</th>
                        <th scope="col">Aangemaakt op</th>
                        <th scope="col">Gewijzigd op</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="customer in customers">
                        <th scope="row">
                            {{ customer.username }} <template v-if="customer.deleted_at">[verwijderd]</template>
                        </th>
                        <td class="d-sm-none d-md-table-cell">
                            <a :href="'mailto:' + customer.contact.contact_email" v-if="customer.contact.contact_email">
                                {{ customer.contact.contact_email }}
                            </a>
                            <span v-else>N.B.</span>
                        </td>
                        <td>{{ customer.active && !customer.deleted_at ? 'Ja' : 'Nee' }}</td>
                        <td>{{ customer.created_at }}</td>
                        <td>{{ customer.updated_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            customers: {
                required: true,
                type: Array
            }
        }
    }
</script>