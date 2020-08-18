<template>
    <div class="container">
        <div class="table">

            <table class="table-sm text-center" style="width:100%;">

                <tbody>

                    <tr>

                        <td>

                            <div class="form-group">

                                <label for="">Start Date</label>

                                <input type="date" class="form-control form-control-sm" name="programs[0][start_date]">

                            </div>

                            <div class="form-group">

                                <label for="">Start Time</label>

                                <input type="time" class="form-control form-control-sm" name="programs[0][start_time]">

                            </div>

                        </td>

                        <td>

                            <div class="form-group">

                                <label for="">End Date</label>

                                <input type="date" class="form-control form-control-sm" name="programs[0][end_date]">

                            </div>

                            <div class="form-group">

                                <label for="">End Time</label>

                                <input type="time" class="form-control form-control-sm" name="programs[0][end_time]">

                            </div>

                        </td>

                        <td>
                            <label for="">Ages/Grades</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <select class="form-control form-control-sm" name="programs[0][ages_type]" id="ages_type">
                                        <option value="" {{ old("ages_types[0]") == '' ? 'selected' : '' }}>Default</option>
                                        <option value="ages" {{ old("ages_types[0]") == 'ages' ? 'selected' : '' }}>Ages</option>
                                        <option value="grades" {{ old("ages_types[0]") == 'grades' ? 'selected' : '' }}>Grades</option>
                                    </select>
                                </div>
                                <input type="number" aria-label="Minimum" placeholder="Minimum" name="programs[0][min_age]" value="{{ old("min_ages[0]") }}" class="form-control form-control-sm">
                                <input type="number" aria-label="Maximum" placeholder="Maximum" name="programs[0][max_age]" value="{{ old("max_ages[0]") }}" class="form-control form-control-sm">
                            </div>
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
