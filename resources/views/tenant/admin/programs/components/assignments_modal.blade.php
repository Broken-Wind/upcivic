<div class="modal fade" id="assignmentsModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="assignmentsModal">Assign Tasks to Partners of Selected Programs</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">
                @forelse($tasks as $task)
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="task_ids[]" id="taskId{{ $task->id }}" value="{{ $task->id }}" form="bulk_action">
                        {{ $task->name }}
                      </label>
                    </div>
                @empty
                @endforelse
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="bulk_action" name="action" value="assign_tasks">Assign Tasks</button>
            </div>

        </div>

    </div>

</div>
