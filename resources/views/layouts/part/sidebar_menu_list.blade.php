<ul class="nav flex-column mb-auto sidebar-menu-list">
      <li class="nav-item   {{ Route::is('users.profile') ? 'active' : '' }}" >
        <a href="{{ route('users.profile') }}" class="nav-link svg-stroke" aria-current="page">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M12 11C12.5253 11 13.0454 10.8965 13.5307 10.6955C14.016 10.4945 14.457 10.1999 14.8284 9.82843C15.1999 9.45699 15.4945 9.01604 15.6955 8.53073C15.8965 8.04543 16 7.52529 16 7C16 6.47471 15.8965 5.95457 15.6955 5.46927C15.4945 4.98396 15.1999 4.54301 14.8284 4.17157C14.457 3.80014 14.016 3.5055 13.5307 3.30448C13.0454 3.10346 12.5253 3 12 3C10.9391 3 9.92172 3.42143 9.17157 4.17157C8.42143 4.92172 8 5.93913 8 7C8 8.06087 8.42143 9.07828 9.17157 9.82843C9.92172 10.5786 10.9391 11 12 11Z"  stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M3 20.4V21H21V20.4C21 18.16 21 17.04 20.564 16.184C20.1805 15.4314 19.5686 14.8195 18.816 14.436C17.96 14 16.84 14 14.6 14H9.4C7.16 14 6.04 14 5.184 14.436C4.43139 14.8195 3.81949 15.4314 3.436 16.184C3 17.04 3 18.16 3 20.4Z"  stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
          Профиль
        </a>
      </li>
      <li class="">
        <a href="{{ route('income_calculator') }}" class="nav-link  svg-fill ">
          <svg width="24" height="24"  viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M12.7889 17.7709C12.7342 17.7216 12.6747 17.6777 12.6111 17.64C12.5544 17.5968 12.4905 17.5636 12.4222 17.5418C12.3561 17.5086 12.2847 17.4865 12.2111 17.4764C11.9982 17.4322 11.7766 17.4551 11.5778 17.5418C11.4399 17.5908 11.3147 17.669 11.211 17.7708C11.1073 17.8726 11.0277 17.9955 10.9778 18.1309C10.9156 18.2604 10.8851 18.4024 10.8889 18.5455C10.8871 18.6873 10.9135 18.8281 10.9667 18.96C11.0311 19.0904 11.1133 19.2115 11.2111 19.32C11.3149 19.4211 11.4381 19.5011 11.5734 19.5554C11.7088 19.6097 11.8538 19.6372 12 19.6364C12.1458 19.6401 12.2904 19.6102 12.4222 19.5491C12.5569 19.4938 12.6809 19.4164 12.7889 19.32C12.8919 19.2181 12.9733 19.0972 13.0286 18.9643C13.0839 18.8314 13.112 18.689 13.1111 18.5455C13.1094 18.4029 13.0792 18.2621 13.0222 18.1309C12.9693 17.997 12.89 17.8747 12.7889 17.7709ZM8.16667 13.2764C8.10994 13.2332 8.04604 13.2 7.97778 13.1782C7.91306 13.1374 7.84164 13.1078 7.76667 13.0909C7.62413 13.0583 7.47587 13.0583 7.33333 13.0909L7.13333 13.1564L6.93333 13.2545L6.76667 13.3855C6.55837 13.6 6.44287 13.8854 6.44444 14.1818C6.4436 14.3254 6.47163 14.4677 6.52692 14.6006C6.58221 14.7335 6.66369 14.8544 6.76667 14.9564C6.87467 15.0527 6.99868 15.1302 7.13333 15.1855C7.30166 15.2534 7.48441 15.2796 7.6655 15.2619C7.84659 15.2443 8.02048 15.1832 8.1719 15.0841C8.32332 14.985 8.44763 14.8509 8.5339 14.6935C8.62017 14.5362 8.66576 14.3605 8.66667 14.1818C8.66257 13.893 8.54748 13.6163 8.34444 13.4073L8.16667 13.2764ZM8.34444 17.7709C8.23877 17.6716 8.11417 17.5937 7.97778 17.5418C7.77664 17.4503 7.55221 17.4199 7.33333 17.4545L7.13333 17.52C7.06253 17.5439 6.99527 17.5769 6.93333 17.6182C6.87513 17.6585 6.81947 17.7022 6.76667 17.7491C6.66551 17.8528 6.58622 17.9752 6.53333 18.1091C6.47455 18.2397 6.44419 18.3809 6.44419 18.5236C6.44419 18.6664 6.47455 18.8076 6.53333 18.9382C6.5896 19.0704 6.66852 19.1921 6.76667 19.2982C6.86864 19.4032 6.99094 19.4873 7.1264 19.5453C7.26186 19.6034 7.40776 19.6343 7.55556 19.6364C7.70131 19.6401 7.84591 19.6102 7.97778 19.5491C8.11243 19.4938 8.23644 19.4164 8.34444 19.32C8.44259 19.214 8.52151 19.0922 8.57778 18.96C8.63656 18.8294 8.66693 18.6882 8.66693 18.5455C8.66693 18.4027 8.63656 18.2615 8.57778 18.1309C8.5249 17.997 8.4456 17.8747 8.34444 17.7709ZM11.5778 13.1782C11.4414 13.2301 11.3168 13.308 11.2111 13.4073C11.0081 13.6163 10.893 13.893 10.8889 14.1818C10.8898 14.3605 10.9354 14.5362 11.0217 14.6935C11.1079 14.8509 11.2322 14.985 11.3837 15.0841C11.5351 15.1832 11.709 15.2443 11.8901 15.2619C12.0712 15.2796 12.2539 15.2534 12.4222 15.1855C12.5569 15.1302 12.6809 15.0527 12.7889 14.9564C12.8919 14.8544 12.9733 14.7335 13.0286 14.6006C13.0839 14.4677 13.112 14.3254 13.1111 14.1818C13.107 13.893 12.9919 13.6163 12.7889 13.4073C12.6326 13.2559 12.4342 13.1534 12.2187 13.1126C12.0031 13.0718 11.7801 13.0946 11.5778 13.1782ZM17.2333 17.7709C17.1253 17.6745 17.0013 17.5971 16.8667 17.5418C16.6643 17.4583 16.4413 17.4355 16.2258 17.4762C16.0102 17.517 15.8118 17.6195 15.6556 17.7709C15.5544 17.8747 15.4751 17.997 15.4222 18.1309C15.3634 18.2615 15.3331 18.4027 15.3331 18.5455C15.3331 18.6882 15.3634 18.8294 15.4222 18.96C15.4785 19.0922 15.5574 19.214 15.6556 19.32C15.7594 19.4211 15.8825 19.5011 16.0179 19.5554C16.1533 19.6097 16.2982 19.6372 16.4444 19.6364C16.5902 19.6401 16.7348 19.6102 16.8667 19.5491C17.0013 19.4938 17.1253 19.4164 17.2333 19.32C17.3875 19.1666 17.4919 18.9718 17.5335 18.7602C17.575 18.5485 17.5518 18.3296 17.4667 18.1309C17.4138 17.997 17.3345 17.8747 17.2333 17.7709ZM16.4444 4.36364H7.55556C7.26087 4.36364 6.97826 4.47857 6.76988 4.68316C6.56151 4.88774 6.44444 5.16522 6.44444 5.45455V9.81818C6.44444 10.1075 6.56151 10.385 6.76988 10.5896C6.97826 10.7942 7.26087 10.9091 7.55556 10.9091H16.4444C16.7391 10.9091 17.0217 10.7942 17.2301 10.5896C17.4385 10.385 17.5556 10.1075 17.5556 9.81818V5.45455C17.5556 5.16522 17.4385 4.88774 17.2301 4.68316C17.0217 4.47857 16.7391 4.36364 16.4444 4.36364ZM15.3333 8.72727H8.66667V6.54545H15.3333V8.72727ZM18.6667 0H5.33333C4.44928 0 3.60143 0.344804 2.97631 0.95856C2.35119 1.57232 2 2.40475 2 3.27273V20.7273C2 21.5953 2.35119 22.4277 2.97631 23.0414C3.60143 23.6552 4.44928 24 5.33333 24H18.6667C19.5507 24 20.3986 23.6552 21.0237 23.0414C21.6488 22.4277 22 21.5953 22 20.7273V3.27273C22 2.40475 21.6488 1.57232 21.0237 0.95856C20.3986 0.344804 19.5507 0 18.6667 0ZM19.7778 20.7273C19.7778 21.0166 19.6607 21.2941 19.4523 21.4987C19.244 21.7032 18.9614 21.8182 18.6667 21.8182H5.33333C5.03865 21.8182 4.75603 21.7032 4.54766 21.4987C4.33929 21.2941 4.22222 21.0166 4.22222 20.7273V3.27273C4.22222 2.9834 4.33929 2.70592 4.54766 2.50134C4.75603 2.29675 5.03865 2.18182 5.33333 2.18182H18.6667C18.9614 2.18182 19.244 2.29675 19.4523 2.50134C19.6607 2.70592 19.7778 2.9834 19.7778 3.27273V20.7273ZM17.0556 13.2764C16.9988 13.2332 16.9349 13.2 16.8667 13.1782C16.8005 13.145 16.7292 13.1228 16.6556 13.1127C16.513 13.0801 16.3648 13.0801 16.2222 13.1127L16.0222 13.1782L15.8222 13.2764L15.6556 13.4073C15.4525 13.6163 15.3374 13.893 15.3333 14.1818C15.3342 14.3605 15.3798 14.5362 15.4661 14.6935C15.5524 14.8509 15.6767 14.985 15.8281 15.0841C15.9795 15.1832 16.1534 15.2443 16.3345 15.2619C16.5156 15.2796 16.6983 15.2534 16.8667 15.1855C17.0013 15.1302 17.1253 15.0527 17.2333 14.9564C17.3363 14.8544 17.4178 14.7335 17.4731 14.6006C17.5284 14.4677 17.5564 14.3254 17.5556 14.1818C17.5515 13.893 17.4364 13.6163 17.2333 13.4073L17.0556 13.2764Z" />
</svg>
          Калькулятор доходов
        </a>
      </li>
      <li class="">
        <a href="#" class="nav-link svg-stroke ">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M21 13.65V2.1C21 1.80826 20.8815 1.52847 20.6705 1.32218C20.4595 1.11589 20.1734 1 19.875 1H4.125C3.82663 1 3.54048 1.11589 3.32951 1.32218C3.11853 1.52847 3 1.80826 3 2.1V21.9C3 22.1917 3.11853 22.4715 3.32951 22.6778C3.54048 22.8841 3.82663 23 4.125 23H10.3125M8.0625 5.4H15.9375M8.0625 9.8H15.9375M8.0625 14.2H11.4375"  stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M20 23L18.2653 20.9863M18.2653 20.9863C18.5025 20.7177 18.6915 20.3971 18.8212 20.0431C18.9509 19.6891 19.0188 19.3087 19.0208 18.924C19.0229 18.5393 18.9592 18.158 18.8333 17.8021C18.7074 17.4463 18.5219 17.123 18.2876 16.8509C18.0532 16.5789 17.7747 16.3637 17.4681 16.2176C17.1615 16.0715 16.833 15.9976 16.5016 16.0001C16.1702 16.0025 15.8426 16.0813 15.5376 16.232C15.2327 16.3826 14.9565 16.602 14.7252 16.8774C14.2592 17.4233 13.9985 18.1612 14 18.9296C14.0015 19.6981 14.2653 20.4346 14.7334 20.9779C15.2016 21.5212 15.8361 21.8271 16.4981 21.8287C17.1601 21.8302 17.7952 21.5274 18.2653 20.9863Z"  stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

          Заключение договора
        </a>
      </li>
        <li class=" {{ Route::is('users.contracts') ? 'active' : '' }}">
        <a href="{{ route('users.contracts') }}" class="nav-link svg-fill  ">
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16 0H8C6.9 0 6 0.9 6 2V18C6 19.1 6.9 20 8 20H20C21.1 20 22 19.1 22 18V6L16 0ZM20 18H8V2H15V7H20V18ZM4 4V22H20V24H4C2.9 24 2 23.1 2 22V4H4ZM10 10V12H18V10H10ZM10 14V16H15V14H10Z" />
</svg>


          Активные договора
        </a>
      </li>
      <li class=" {{ Route::is('payments.for_user') ? 'active' : '' }}">
        <a href="{{ route('payments.for_user') }}" class="nav-link svg-fill svg-stroke ">
  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0_94_2790)">
<path d="M12 15.1H12.0001C12.9757 15.099 13.9139 14.7757 14.6083 14.197C15.3032 13.6179 15.6988 12.8285 15.7 12.0002V12C15.7 11.3824 15.4801 10.7806 15.0713 10.2707C14.6628 9.7612 14.0841 9.36642 13.4103 9.13384C12.7365 8.90125 11.9958 8.84056 11.2813 8.95899C10.5669 9.07742 9.90862 9.37001 9.3904 9.80186C8.87202 10.2338 8.5163 10.7866 8.37191 11.3915C8.22742 11.9968 8.30185 12.6239 8.58449 13.1926C8.86691 13.7607 9.3436 14.2434 9.9513 14.5818C10.559 14.9201 11.2719 15.1 12 15.1ZM19.2 10.4C18.8279 10.4 18.463 10.4919 18.1513 10.6654C17.8396 10.839 17.5937 11.0874 17.4475 11.3815C17.3011 11.676 17.2623 12.0015 17.3373 12.3159C17.4123 12.6298 17.5965 12.9153 17.8632 13.1375C18.1297 13.3596 18.4672 13.5093 18.8325 13.5698C19.1978 13.6304 19.5765 13.5994 19.9215 13.4803C20.2664 13.3613 20.5639 13.1588 20.7747 12.8959C20.9857 12.6327 21.1 12.321 21.1 12C21.1 11.5692 20.8945 11.1606 20.5368 10.8625C20.1797 10.5649 19.6986 10.4 19.2 10.4ZM11.0486 10.8402C11.329 10.6841 11.6601 10.6 11.9999 10.6C12.456 10.6004 12.8903 10.7517 13.2082 11.0166C13.5256 11.2811 13.6996 11.6354 13.7 12.0001C13.7 12.2725 13.6032 12.5407 13.4186 12.7708C13.2338 13.0013 12.9691 13.1833 12.6562 13.2913C12.3433 13.3993 11.9982 13.4277 11.6652 13.3725C11.3321 13.3173 11.0282 13.1813 10.7912 12.9838C10.5544 12.7865 10.3958 12.5374 10.3319 12.2694C10.268 12.0018 10.3005 11.7241 10.4266 11.4705C10.5529 11.2164 10.7683 10.9963 11.0486 10.8402ZM4.8 10.4C4.42786 10.4 4.06298 10.4919 3.75133 10.6654C3.43964 10.839 3.19365 11.0874 3.04747 11.3815C2.90107 11.676 2.86229 12.0015 2.93732 12.3159C3.01227 12.6298 3.19655 12.9153 3.46319 13.1375C3.72968 13.3596 4.06723 13.5093 4.43249 13.5698C4.79777 13.6304 5.17654 13.5994 5.52146 13.4803C5.86635 13.3613 6.1639 13.1588 6.37466 12.8959C6.58567 12.6327 6.7 12.321 6.7 12C6.7 11.5692 6.49448 11.1606 6.13681 10.8625C5.77967 10.5649 5.29861 10.4 4.8 10.4Z"  stroke-width="0.2"/>
<path d="M22.2857 19.1L22.2859 19.1C22.7675 19.0993 23.2287 18.9036 23.5686 18.5566C23.9084 18.2097 24.0993 17.7399 24.1 17.2501V17.25V6.75V6.74976C24.0988 6.2602 23.9078 5.79064 23.5681 5.44386C23.2283 5.09701 22.7674 4.90122 22.286 4.9H22.2857L1.71429 4.9L1.71403 4.9C1.23262 4.90122 0.771689 5.09701 0.431917 5.44386C0.092212 5.79064 -0.0988053 6.2602 -0.1 6.74976V6.75L-0.1 17.25L-0.0999997 17.2502C-0.0988053 17.7398 0.0922119 18.2094 0.431917 18.5561C0.771689 18.903 1.23262 19.0988 1.71403 19.1H1.71429H22.2857ZM1.81429 6.85H22.1857V17.15H1.81429V6.85Z"  stroke-width="0.2"/>
</g>
<defs>
<clipPath id="clip0_94_2790">
<rect width="24" height="24" fill="white"/>
</clipPath>
</defs>
</svg>

         График платежей
        </a>
      </li>
      
      
         @can('see other profiles')
                        @if (Route::has('users.index'))
                            <li class="">
                                <a class="nav-link" href="{{ route('users.index') }}">Пользователи</a>
                            </li>
                        @endif
                    @endcan
					@can('change settings')
                        @if (Route::has('settings.index'))
                            <li class="">
                                <a class="nav-link" href="{{ route('settings.index') }}">Настройки</a>
                            </li>
                        @endif
                    @endcan
      
      
      
      
      <li class="">
        <a href="{{ route('logout') }}" class="svg-stroke nav-link">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M14.7692 8.5V6.75C14.7692 6.28587 14.5869 5.84075 14.2623 5.51256C13.9377 5.18437 13.4975 5 13.0385 5H4.73077C4.27174 5 3.83151 5.18437 3.50693 5.51256C3.18235 5.84075 3 6.28587 3 6.75V17.25C3 17.7141 3.18235 18.1592 3.50693 18.4874C3.83151 18.8156 4.27174 19 4.73077 19H13.0385C13.4975 19 13.9377 18.8156 14.2623 18.4874C14.5869 18.1592 14.7692 17.7141 14.7692 17.25V15.5M17.5385 8.5L21 12M21 12L17.5385 15.5M21 12H9.1875"  stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
          Выйти
        </a>
      </li>
      
      
      
    </ul>
    