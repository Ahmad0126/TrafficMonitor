@if ($paginator->hasPages())
    <div class="dataTables_paginate paging_simple_numbers p-4">
        <ul class="pagination" style="float: right; gap: 5px;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <li class="paginate_button page-item previous disabled">
                        <span class="page-link">
                            Previous
                        </span>
                    </li>
                </li>
            @else
                <li>
                    <li class="paginate_button page-item previous">
                        <a href="{{ $paginator->previousPageUrl() }}" class="page-link">
                            Previous
                        </a>
                    </li>
                </li>
            @endif
            
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="paginate_button page-item active">
                                <a href="#" class="page-link">
                                    {{ $page }}
                                </a>
                            </li>
                        @else
                            <li class="paginate_button page-item">
                                <a href="{{ $url }}" class="page-link">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach
            
            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="paginate_button page-item next">
                    <a href="{{ $paginator->nextPageUrl() }}" class="page-link">
                        Next
                    </a>
                </li>
            @else
                <li class="paginate_button page-item next">
                    <span class="page-link">
                        Next
                    </span>
                </li>
            @endif
        </ul>
    </div>
@endif
