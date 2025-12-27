@if ($paginator->hasPages())
    <nav>
        <ul class="pagination" style="margin: 0; display: flex; list-style: none; padding: 0; gap: 8px; justify-content: center;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" style="padding: 8px 12px; border: 2px solid #e3eae8; border-radius: 8px; background: #f5f5f5; color: #999; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 500; cursor: not-allowed;">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')" style="padding: 8px 12px; border: 2px solid #e3eae8; border-radius: 8px; background: white; color: var(--primary-color); font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 500; text-decoration: none; transition: all 0.3s ease; display: block;"
                       onmouseover="this.style.borderColor='var(--primary-color)'; this.style.background='#f0f8f5'"
                       onmouseout="this.style.borderColor='#e3eae8'; this.style.background='white'">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link" style="padding: 8px 12px; border: 2px solid #e3eae8; border-radius: 8px; background: white; color: #999; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 500;">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link" style="padding: 8px 12px; border: 2px solid var(--primary-color); border-radius: 8px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; box-shadow: 0 2px 8px rgba(18, 130, 65, 0.2);">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}" style="padding: 8px 12px; border: 2px solid #e3eae8; border-radius: 8px; background: white; color: #2c3e50; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 500; text-decoration: none; transition: all 0.3s ease; display: block;"
                                   onmouseover="this.style.borderColor='var(--primary-color)'; this.style.background='#f0f8f5'; this.style.color='var(--primary-color)'"
                                   onmouseout="this.style.borderColor='#e3eae8'; this.style.background='white'; this.style.color='#2c3e50'">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" style="padding: 8px 12px; border: 2px solid #e3eae8; border-radius: 8px; background: white; color: var(--primary-color); font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 500; text-decoration: none; transition: all 0.3s ease; display: block;"
                       onmouseover="this.style.borderColor='var(--primary-color)'; this.style.background='#f0f8f5'"
                       onmouseout="this.style.borderColor='#e3eae8'; this.style.background='white'">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" style="padding: 8px 12px; border: 2px solid #e3eae8; border-radius: 8px; background: #f5f5f5; color: #999; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 500; cursor: not-allowed;">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
