@php
    $navItems = [
        [
            'label' => 'Dashboard overview',
            'desc' => 'Metrics & priorities',
            'route' => route('company.dashboard'),
            'match' => ['company.dashboard'],
            'background' => '#eef2ff',
            'border' => '#c7d2fe',
        ],
        [
            'label' => 'Manage jobs',
            'desc' => 'Edit, pause, add',
            'route' => route('company.jobs.index'),
            'match' => ['company.jobs.*'],
            'background' => '#ecfdf5',
            'border' => '#bbf7d0',
        ],
        [
            'label' => 'Applicants & chat',
            'desc' => 'Follow-up seekers',
            'route' => route('company.applications.index'),
            'match' => ['company.applications.*'],
            'background' => '#fff1f2',
            'border' => '#fecdd3',
        ],
        [
            'label' => 'Brand profile',
            'desc' => 'Logo, banner, story',
            'route' => route('company.profile.edit'),
            'match' => ['company.profile.edit'],
            'background' => '#fffbeb',
            'border' => '#fde68a',
        ],
        [
            'label' => 'Post new job',
            'desc' => 'Share upcoming roles',
            'route' => route('company.jobs.create'),
            'match' => ['company.jobs.create'],
            'background' => '#e0f2fe',
            'border' => '#bae6fd',
        ],
        [
            'label' => 'Packages',
            'desc' => 'Select & manage plan',
            'route' => route('company.packages.index'),
            'match' => ['company.packages.*'],
            'background' => '#f3e8ff',
            'border' => '#d8b4fe',
        ],
    ];
@endphp

<aside style="flex:0 0 280px; border-right:1px solid #e5e7eb; padding:30px; background:#f8fafc;">
    <nav style="display:grid; gap:12px;">
        @foreach($navItems as $item)
            @php
                $active = request()->routeIs($item['match']);
            @endphp
            <a href="{{ $item['route'] }}" class="card"
                style="border:1px dashed {{ $item['border'] }}; background:{{ $active ? '#ffffff' : $item['background'] }}; box-shadow:{{ $active ? '0 0 0 2px rgba(15,76,117,.12)' : 'none' }};">
                <strong>{{ $item['label'] }}</strong>
                <span style="font-size:13px; color:#475569;">{{ $item['desc'] }}</span>
            </a>
        @endforeach
    </nav>
</aside>

