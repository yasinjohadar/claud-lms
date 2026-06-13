@extends('frontend.layouts.master')

@section('title', $resource->title . ' — موارد ' . $course->title)

@section('body_class', 'course-resource-detail-page')

@section('content')
    @include('frontend.pages.course-resources.partials.hero', ['course' => $course, 'resource' => $resource])

    <main class="container course-resource-detail-main py-4 py-md-5">
        <div class="row g-4 g-lg-5">
            <div class="col-lg-8">
                <article class="course-resource-detail section-fade-up">
                    @if($resource->description)
                        <div class="course-resource-detail__section">
                            <h2 class="course-resource-detail__heading">عن هذا المورد</h2>
                            <div class="course-resource-detail__description text-secondary lh-lg">
                                {!! nl2br(e($resource->description)) !!}
                            </div>
                        </div>
                    @endif

                    <div class="course-resource-detail__section">
                        <h2 class="course-resource-detail__heading">الإجراءات</h2>
                        <div class="course-resource-detail__actions d-flex flex-wrap gap-3">
                            @if($resource->isLink())
                                <a href="{{ $resource->url }}" target="_blank" rel="noopener noreferrer" class="btn btn-accent btn-lg">
                                    <i class="fas fa-external-link-alt me-2"></i> فتح الرابط
                                </a>
                            @else
                                <a href="{{ route('courses.resources.download', [$course->slug, $resource->slug]) }}" class="btn btn-accent btn-lg">
                                    <i class="fas fa-download me-2"></i> تحميل الملف
                                </a>
                                @if($resource->file_original_name)
                                    <span class="course-resource-detail__file-meta align-self-center text-secondary small">
                                        {{ $resource->file_original_name }}
                                        @if($resource->formatted_file_size)
                                            &bull; {{ $resource->formatted_file_size }}
                                        @endif
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>

                    @if($resource->isFile())
                        @php
                            $mime = $resource->file_mime ?? '';
                            $isPdf = $mime === 'application/pdf';
                            $isImage = str_starts_with($mime, 'image/');
                        @endphp

                        @if(($isPdf || $isImage) && $resource->file_url)
                            <div class="course-resource-detail__section">
                                <h2 class="course-resource-detail__heading">معاينة</h2>
                                <div class="course-resource-detail__preview">
                                    @if($isPdf)
                                        <iframe src="{{ $resource->file_url }}" title="{{ $resource->title }}" class="course-resource-preview-frame"></iframe>
                                    @else
                                        <img src="{{ $resource->file_url }}" alt="{{ $resource->title }}" class="course-resource-preview-image img-fluid rounded">
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif
                </article>
            </div>

            <div class="col-lg-4">
                <div class="course-resource-sidebar-sticky position-sticky">
                    @include('frontend.pages.course-resources.partials.sidebar', [
                        'course' => $course,
                        'resources' => $resources,
                        'activeResource' => $resource,
                    ])
                </div>
            </div>
        </div>
    </main>
@endsection
