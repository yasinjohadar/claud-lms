{{-- Admin sidebar links for exam module — merge into admin sidebar --}}

<li class="slide">
    <a href="{{ route('quizzes.index') }}" class="side-menu__item">
        <i class="fe fe-clipboard side-menu__icon"></i>
        <span class="side-menu__label">الاختبارات</span>
    </a>
</li>
<li class="slide">
    <a href="{{ route('question-bank.index') }}" class="side-menu__item">
        <i class="fe fe-database side-menu__icon"></i>
        <span class="side-menu__label">بنك الأسئلة</span>
    </a>
</li>
<li class="slide">
    <a href="{{ route('question-pools.index') }}" class="side-menu__item">
        <i class="fe fe-layers side-menu__icon"></i>
        <span class="side-menu__label">مجموعات الأسئلة</span>
    </a>
</li>
<li class="slide">
    <a href="{{ route('question-modules.index') }}" class="side-menu__item">
        <i class="fe fe-help-circle side-menu__icon"></i>
        <span class="side-menu__label">وحدات الأسئلة</span>
    </a>
</li>
<li class="slide">
    <a href="{{ route('grading.index') }}" class="side-menu__item">
        <i class="fe fe-check-square side-menu__icon"></i>
        <span class="side-menu__label">تصحيح الاختبارات</span>
    </a>
</li>
