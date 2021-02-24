<div class="progress" style="height: 20px">
    <div class="progress-bar {{ $program->progress_bar_class }}" role="progressbar" style="width: {{ $program->enrollment_percent }}%;" aria-valuenow="{{ $program->enrollment_percent }}" aria-valuemin="0" aria-valuemax="100"></div>
    <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ 100 - $program->enrollment_percent }}%;" aria-valuenow="{{ 100 - $program->enrollment_percent }}" aria-valuemin="0" aria-valuemax="100"></div>
    <div class="progress-bar-title" style="position: absolute; text-align: center; line-height: 20px; overflow: hidden; color: #fff; right: 0; left: 0; top: 0;">
        @if($program->isFull())
            FULL at {{ $program->enrollments }} enrolled
        @else
            {{ $program->enrollments }} of {{ $program->max_enrollments }} enrolled
        @endif
    </div>
</div>
