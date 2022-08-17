@extends('layouts.admin-page-wide')

@section('pageTitle',__('site.order').': '.$order->user->name)
@section('page-title',__('site.order').': '.$order->user->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        @can('access','view_orders')
                        <a href="{{ url('/admin/orders') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        @endcan

                        @can('access','edit_order')
                        <a href="{{ url('/admin/orders/' . $order->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>
                        @endcan

                        @can('access','delete_order')
                        <form method="POST" action="{{ url('admin/orders' . '/' . $order->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        @endcan


                        <br/>
                        <br/>

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#home">@lang('site.order-details')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu1">@lang('site.employer-details')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu2">@lang('site.shortlist')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu3">@lang('site.invoices')</a>
                            </li>
                            @can('access','create_interview')
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu4">@lang('site.create-interview')</a>
                            </li>
                            @endcan
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane container active" id="home">


                                <div class="accordion" id="accordionExample">
                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                    @lang('site.general-details')
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-md-6 {{ $errors->has('user_id') ? 'has-error' : ''}}">
                                                        <label for="user_id" class="control-label">@lang('site.employer')</label>

                                                        <div><a href="{{ route('admin.employers.show',['employer'=>$order->user_id]) }}">{{ $order->user->name }}</a></div>

                                                    </div>
                                                    <div class="form-group col-md-6  ">
                                                        <label for="added" class="control-label">@lang('site.added-on')</label>
                                                            <div>{{ \Illuminate\Support\Carbon::parse($order->created_at)->format('d/M/Y') }}</div>


                                                    </div>
                                                </div>



                                                <div class="row">


                                                    <div class="form-group col-md-6 {{ $errors->has('status') ? 'has-error' : ''}}">
                                                        <label for="status" class="control-label">@lang('site.status')</label>
                                                        <div>{{ orderStatus($order->status) }}</div>

                                                    </div>

                                                    <div class="form-group col-md-6 {{ $errors->has('interview_date') ? 'has-error' : ''}}">
                                                        <label for="interview_date" class="control-label">@lang('site.interview-date')</label>
                                                        @if(!empty($order->interview_date))
                                                            <div>{{ \Illuminate\Support\Carbon::parse($order->interview_date)->format('d/M/Y') }}</div>
                                                        @endif

                                                    </div>

                                                </div>


                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label>@lang('site.interview-location')</label>
                                                        <div>{!! clean( nl2br(clean($order->interview_location)) ) !!}</div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>@lang('site.interview-time')</label>
                                                        <div>{{ $order->interview_time }}</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label>@lang('site.comments')</label>
                                                        <div>{!! clean( nl2br(clean($order->comments)) ) !!}</div>
                                                    </div>
                                                </div>





                                            </div>
                                        </div>


                                    </div>
                                    @foreach(\App\OrderFieldGroup::where('order_form_id',$order->order_form_id)->orderBy('sort_order')->get() as $group)
                                        <div class="card">
                                            <div class="card-header" id="heading{{ $group->id }}">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse{{ $group->id }}" aria-expanded="false" aria-controls="collapse{{ $group->id }}">
                                                        {{ $group->name }}
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="collapse{{ $group->id }}" class="collapse" aria-labelledby="heading{{ $group->id }}" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach($group->orderFields()->orderBy('sort_order')->get() as $field)
                                                            <?php


                                                            $value = ($order->orderFields()->where('id',$field->id)->first()) ? $order->orderFields()->where('id',$field->id)->first()->pivot->value:'';
                                                            ?>

                                                            @if($field->type=='text')
                                                                <div class="col-md-6{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                                    <div>{{ $value }}</div>
                                                                </div>
                                                            @elseif($field->type=='select')
                                                                <div class="col-md-6{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                                    <div>{{ $value }}</div>

                                                                </div>
                                                            @elseif($field->type=='textarea')
                                                                <div class="col-md-6{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                                    <div>{{ $value }}</div>
                                                                </div>
                                                            @elseif($field->type=='checkbox')
                                                                <div class="col-md-6{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                                    <div>{{ boolToString($value) }}</div>
                                                                </div>

                                                            @elseif($field->type=='radio')
                                                                <div class="col-md-6{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                                    <div>{{ $value }}</div>

                                                                </div>
                                                            @elseif($field->type=='file')


                                                                <div class="col-md-6">
                                                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>


                                                                    @if(!empty($value))
                                                                        <h3>{{ basename($value) }}</h3>
                                                                        @if(isImage($value))
                                                                            <div><img  data-toggle="modal" data-target="#pictureModal{{ $field->id }}" src="{{ route('admin.image') }}?file={{ $value }}"  class="int_w330cur" /></div> <br/>


                                                                            <div class="modal fade" id="pictureModal{{ $field->id }}" tabindex="-1" role="dialog" aria-labelledby="pictureModal{{ $field->id }}Label" aria-hidden="true">
                                                                                <div class="modal-dialog modal-lg" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title" id="pictureModal{{ $field->id }}Label">@lang('site.picture')</h5>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body int_txcen"  >
                                                                                            <img src="{{ route('admin.image') }}?file={{ $value }}" class="int_txcen" />
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('site.close')</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>



                                                                        @endif
                                                                        <a class="btn btn-success" href="{{ route('admin.download') }}?file={{ $value }}"><i class="fa fa-download"></i> @lang('site.download')</a>
                                                                    @endif
                                                                </div>


                                                            @endif


                                                        @endforeach
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>




                            </div>
                            <div class="tab-pane container fade" id="menu1">
                                <div class="accordion" id="e-accordionExample">
                                    <div class="card">
                                        <div class="card-header" id="e-headingOne">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#e-collapseOne" aria-expanded="true" aria-controls="e-collapseOne">
                                                    @lang('site.general-details')
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="e-collapseOne" class="collapse show" aria-labelledby="e-headingOne" data-parent="#e-accordionExample">
                                            <div class="card-body">

                                                <div class="row">
                                                    <div class=" col-md-6 {{ $errors->has('name') ? 'has-error' : ''}}">
                                                        <label for="name" class="control-label">@lang('site.name')</label>
                                                        <div>{{ $employer->name }}</div>
                                                    </div>
                                                    <div class="col-md-6 {{ $errors->has('gender') ? 'has-error' : ''}}">
                                                        <label for="gender" class="control-label">@lang('site.gender')</label>
                                                        <div>{{ gender($employer->employer->gender) }}</div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 {{ $errors->has('email') ? 'has-error' : ''}}">
                                                        <label for="email" class="control-label">@lang('site.email')</label>
                                                        <div>{{ $employer->email }}</div>
                                                    </div>
                                                    <div class="col-md-6 {{ $errors->has('telephone') ? 'has-error' : ''}}">
                                                        <label for="telephone" class="control-label">@lang('site.telephone')</label>
                                                        <div>{{ $employer->telephone }}</div>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="col-md-6 {{ $errors->has('gender') ? 'has-error' : ''}}">
                                                        <label for="gender" class="control-label">@lang('site.gender')</label>
                                                        <div>{{ gender($employer->employer->gender) }}</div>
                                                    </div>
                                                    <div class="col-md-6 {{ $errors->has('active') ? 'has-error' : ''}}">
                                                        <label for="active" class="control-label">@lang('site.active')</label>
                                                        <div>{{ boolToString($employer->employer->active) }}</div>
                                                    </div>

                                                </div>




                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label  class="control-label">@lang('site.registered-on')</label>
                                                        <div>{{ \Illuminate\Support\Carbon::parse($employer->created_at)->format('d/M/Y') }}</div>
                                                    </div>

                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    @foreach(\App\EmployerFieldGroup::get() as $group)
                                        <div class="card">
                                            <div class="card-header" id="e-heading{{ $group->id }}">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#e-collapse{{ $group->id }}" aria-expanded="false" aria-controls="e-collapse{{ $group->id }}">
                                                        {{ $group->name }}
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="e-collapse{{ $group->id }}" class="collapse" aria-labelledby="e-heading{{ $group->id }}" data-parent="#e-accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach($group->employerFields()->orderBy('sort_order')->get() as $field)
                                                            <?php


                                                            $value = ($employer->employerFields()->where('id',$field->id)->first()) ? $employer->employerFields()->where('id',$field->id)->first()->pivot->value:'';
                                                            ?>

                                                            @if($field->type=='text')
                                                                <div class="col-md-6{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                                    <div>{{ $value }}</div>
                                                                </div>
                                                            @elseif($field->type=='select')
                                                                <div class="col-md-6{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                                    <div>{{ $value }}</div>

                                                                </div>
                                                            @elseif($field->type=='textarea')
                                                                <div class="col-md-6{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                                    <div>{{ $value }}</div>
                                                                </div>
                                                            @elseif($field->type=='checkbox')
                                                                <div class="col-md-6{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                                    <div>{{ boolToString($value) }}</div>
                                                                </div>

                                                            @elseif($field->type=='radio')
                                                                <div class="col-md-6{{ $errors->has('field_'.$field->id) ? ' has-error' : '' }}">
                                                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>
                                                                    <div>{{ $value }}</div>

                                                                </div>
                                                            @elseif($field->type=='file')


                                                                <div class="col-md-6">
                                                                    <label for="{{ 'field_'.$field->id }}">{{ $field->name }}:</label>


                                                                    @if(!empty($value))
                                                                        <h3>{{ basename($value) }}</h3>
                                                                        @if(isImage($value))
                                                                            <div><img  data-toggle="modal" data-target="#pictureModal{{ $field->id }}" src="{{ route('admin.image') }}?file={{ $value }}"  class="int_w330cur" /></div> <br/>


                                                                            <div class="modal fade" id="pictureModal{{ $field->id }}" tabindex="-1" role="dialog" aria-labelledby="pictureModal{{ $field->id }}Label" aria-hidden="true">
                                                                                <div class="modal-dialog modal-lg" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title" id="pictureModal{{ $field->id }}Label">@lang('site.picture')</h5>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="modal-body int_txcen"  >
                                                                                            <img src="{{ route('admin.image') }}?file={{ $value }}" class="int_txcen" />
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('site.close')</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>



                                                                        @endif
                                                                        <a class="btn btn-success" href="{{ route('admin.download') }}?file={{ $value }}"><i class="fa fa-download"></i> @lang('site.download')</a>
                                                                    @endif
                                                                </div>


                                                            @endif


                                                        @endforeach
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>


                            </div>
                            <div class="tab-pane container fade" id="menu2">

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('site.picture')</th>
                                            <th>@lang('site.name')</th>
                                            <th>@lang('site.age')</th>
                                            <th>@lang('site.telephone')</th>
                                            <th>@lang('site.email')</th>
                                            <th>@lang('site.actions')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($order->candidates as $item)
                                            <tr>
                                                <td>{{ $loop->iteration  }}</td>
                                                <td>
                                                    <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                  @if(!empty($item->picture) && file_exists($item->picture))
                      <img   src="{{ asset($item->picture) }}">
                  @else
                      <img   src="{{ asset('img/man.jpg') }}">
                  @endif

              </span>
                                                    </div>


                                                </td>
                                                <td>{{ $item->user->name }}</td>
                                                <td>{{  getAge(\Illuminate\Support\Carbon::parse($item->date_of_birth)->timestamp) }}</td>
                                                <td>{{ $item->user->telephone }}</td>
                                                <td>{{ $item->user->email }}</td>
                                                <td>
                                                    <div class="btn-group dropup">
                                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="ni ni-settings"></i> @lang('site.actions')
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <!-- Dropdown menu links -->
                                                            @can('access','view_candidate')
                                                            <a class="dropdown-item" href="{{ url('/admin/candidates/' . $item->user->id) }}">@lang('site.view')</a>
                                                            @endcan



                                                            @can('access','view_candidate')
                                                            <div class="dropdown-divider"></div>
                                                            <h6 class="dropdown-header">@lang('site.download-profile')</h6>
                                                            <a class="dropdown-item" href="{{ route('admin.candidate.download',['id'=>$item->user->id,'full'=>1]) }}">@lang('site.full-profile')</a>
                                                            <a class="dropdown-item" href="{{ route('admin.candidate.download',['id'=>$item->user->id,'full'=>0]) }}">@lang('site.partial-profile')</a>
                                                            @endcan


                                                        </div>
                                                    </div>

                                                    <div class="btn-group dropup">
                                                        <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa fa-book"></i> @lang('site.records')
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            @can('access','view_candidate_notes')
                                                            <a class="dropdown-item" href="{{ route('admin.notes.index',['user'=>$item->user->id]) }}">@lang('site.notes') ({{ $item->user->userNotes()->count() }})</a>
                                                            @endcan

                                                            @can('access','view_candidate_attachments')
                                                            <a class="dropdown-item" href="{{ route('admin.attachments.index',['user'=>$item->user->id]) }}">@lang('site.attachments') ({{ $item->user->userAttachments()->count() }})</a>
                                                            @endcan



                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>


                            </div>
                            <div class="tab-pane container fade" id="menu3">
                                @can('access','create_invoice')
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                                    <i class="fa fa-plus"></i> @lang('site.create-new')
                                </button>
                                @endcan

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <form action="{{ route('admin.order.create-invoice',['order'=>$order->id]) }}" method="post">
                                        @csrf
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">@lang('site.create-invoice')</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="title"><span class="req">*</span>@lang('site.item')</label>
                                                    <input class="form-control" type="text" name="title" required />
                                                </div>
                                                <div class="form-group">
                                                    <label for="amount"><span class="req">*</span>@lang('site.amount')</label>
                                                    <input class="form-control digit" type="text" name="amount" required />
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">@lang('site.description')</label>
                                                    <textarea class="form-control" name="description" id="description" ></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="paid"><span class="req">*</span>@lang('site.status')</label>
                                                    <select class="form-control" name="paid" id="paid">
                                                        <option value="0">@lang('site.unpaid')</option>
                                                        <option value="1">@lang('site.paid')</option>
                                                    </select>
                                                </div>
                                                <div class="form-group {{ $errors->has('invoice_category_id') ? 'has-error' : ''}}">
                                                    <label for="invoice_category_id" class="control-label">@lang('site.invoice-category')</label>
                                                    <select  class="form-control" name="invoice_category_id" id="invoice_category_id">
                                                        <option value=""></option>
                                                        @foreach(\App\InvoiceCategory::get() as $invoiceCategory)
                                                            <option @if(old('invoice_category_id',isset($invoice->invoice_category_id) ? $invoice->invoice_category_id : '')==$invoiceCategory->id) selected @endif value="{{ $invoiceCategory->id }}">{{ $invoiceCategory->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    {!! clean( check( $errors->first('invoice_category_id', '<p class="help-block">:message</p>')) ) !!}
                                                </div>
                                                <div class="form-group {{ $errors->has('payment_method_id') ? 'has-error' : ''}}">
                                                    <label for="payment_method_id" class="control-label">@lang('site.payment-method')</label>
                                                    <select  class="form-control" name="payment_method_id" id="payment_method_id">
                                                        <option value=""></option>
                                                        @foreach(\App\PaymentMethod::get() as $paymentMethod)
                                                            <option @if(old('payment_method_id',isset($invoice->payment_method_id) ? $invoice->payment_method_id : '')==$paymentMethod->id) selected @endif value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    {!! clean( check( $errors->first('payment_method_id', '<p class="help-block">:message</p>')) ) !!}
                                                </div>



                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('site.close')</button>
                                                <button type="submit" class="btn btn-primary">@lang('site.save')</button>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>


                                <div class="table-responsive int_mt10"  >
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('site.item')</th>
                                            <th>@lang('site.amount')</th>
                                            <th>@lang('site.status')</th>
                                            <th>@lang('site.created-on')</th>
                                            <th>@lang('site.actions')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($order->invoices()->latest()->get() as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->title }} </td>
                                                <td>{!! clean( check( price($item->amount)) ) !!}</td>
                                                <td>{{ ($item->paid==1)? __('site.paid'):__('site.unpaid') }}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d/M/Y') }}
                                                </td>
                                                <td>
                                                    @can('access','approve_invoice')
                                                    @if($item->paid==0)
                                                        <a onclick="return confirm('@lang('site.confirm-approve')')" class="btn btn-success btn-sm" href="{{ route('admin.invoices.approve',['invoice'=>$item->id]) }}"><i class="fa fa-thumbs-up"></i> @lang('site.approve')</a>
                                                    @endif
                                                    @endcan

                                                    @can('access','view_invoice')
                                                    <a href="{{ url('/admin/invoices/' . $item->id) }}" title="@lang('site.view')"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> @lang('site.view')</button></a>
                                                    @endcan
                                                    &nbsp;
                                                    @can('access','delete_invoice')
                                                    <form method="POST" action="{{ url('/admin/invoices' . '/' . $item->id) }}?back=true" accept-charset="UTF-8" class="int_inlinedisp">
                                                        {{ method_field('DELETE') }}
                                                        {{ csrf_field() }}
                                                        <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')&quot;)"><i class="fa fa-trash" aria-hidden="true"></i> @lang('site.delete')</button>
                                                    </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                 </div>

                            </div>
                            @can('access','create_interview')
                            <div class="tab-pane container fade" id="menu4">

                                    <form method="POST" action="{{ url('/admin/interviews') }}" >
                                        @csrf
                                        <div class="modal-body">


                                            <input type="hidden" name="user_id" value="{{ $order->user_id }}"/>

                                            <div class="form-group  {{ $errors->has('candidates') ? 'has-error' : ''}}">

                                                <label for="candidates">@lang('site.candidates')</label>

                                                <select  multiple name="candidates[]" id="candidates" class="form-control select2">
                                                    <option></option>
                                                </select>

                                                {!! clean( $errors->first('candidates', '<p class="help-block">:message</p>') ) !!}
                                            </div>

                                            <div class="form-group {{ $errors->has('interview_date') ? 'has-error' : ''}}">
                                                <label for="interview_date" class="control-label required">@lang('site.interview-date')</label>
                                                <input required class="form-control date" name="interview_date" type="text" id="interview_date" value="{{ old('interview_date',isset($order->interview_date) ? $order->interview_date : '') }}" >
                                                {!! clean( $errors->first('interview_date', '<p class="help-block">:message</p>') ) !!}
                                            </div>
                                            <div class="form-group {{ $errors->has('interview_time') ? 'has-error' : ''}}">
                                                <label for="interview_time" class="control-label\">@lang('site.time')</label>
                                                <input  class="form-control time" name="interview_time" type="text" id="interview_time" value="{{ old('interview_time',isset($order->interview_time) ? $order->interview_time : '') }}" >
                                                {!! clean( $errors->first('interview_time', '<p class="help-block">:message</p>') ) !!}
                                            </div>
                                            <div class="form-group {{ $errors->has('venue') ? 'has-error' : ''}}">
                                                <label for="venue" class="control-label">@lang('site.venue')</label>
                                                <textarea class="form-control" rows="5" name="venue" type="textarea" id="venue" >{{ old('venue',isset($order->interview_location) ? $order->interview_location : '') }}</textarea>
                                                {!! clean( $errors->first('venue', '<p class="help-block">:message</p>') ) !!}
                                            </div>
                                            <div class="form-group {{ $errors->has('internal_note') ? 'has-error' : ''}}">
                                                <label for="internal_note" class="control-label">@lang('site.internal-note')</label>
                                                <textarea class="form-control" rows="5" name="internal_note" type="textarea" id="internal_note" >{{ old('internal_note') }}</textarea>
                                                {!! clean( $errors->first('internal_note', '<p class="help-block">:message</p>') ) !!}
                                            </div>
                                            <div class="form-group {{ $errors->has('employer_comment') ? 'has-error' : ''}}">
                                                <label for="employer_comment" class="control-label">@lang('site.employer-comment')</label>
                                                <textarea class="form-control" rows="5" name="employer_comment" type="textarea" id="employer_comment" >{{ old('employer_comment') }}</textarea>
                                                {!! clean( $errors->first('employer_comment', '<p class="help-block">:message</p>') ) !!}
                                            </div>
                                            <div class="form-group {{ $errors->has('reminder') ? 'has-error' : ''}}">
                                                <label for="reminder" class="control-label">@lang('site.send-reminder')</label>
                                                <select name="reminder" class="form-control" id="reminder" >
                                                    @foreach (json_decode('{"1":"Yes","0":"No"}', true) as $optionKey => $optionValue)
                                                        <option value="{{ $optionKey }}" {{ ((null !== old('reminder',@$order->reminder)) && old('reminder',@$order->reminder) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                                                    @endforeach
                                                </select>
                                                {!! clean( $errors->first('reminder', '<p class="help-block">:message</p>') ) !!}
                                            </div>
                                            <div class="form-group {{ $errors->has('feedback') ? 'has-error' : ''}}">
                                                <label for="feedback" class="control-label">@lang('site.request-feedback')</label>
                                                <select name="feedback" class="form-control" id="feedback" >
                                                    @foreach (json_decode('{"1":"Yes","0":"No"}', true) as $optionKey => $optionValue)
                                                        <option value="{{ $optionKey }}" {{ ((null !== old('feedback',@$order->feedback)) && old('feedback',@$order->feedback) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                                                    @endforeach
                                                </select>
                                                {!! clean( $errors->first('feedback', '<p class="help-block">:message</p>') ) !!}
                                            </div>




                                        </div>
                                            <button type="submit" class="btn btn-primary btn-block">@lang('site.save-changes')</button>

                                    </form>

                            </div>
                            @endcan
                        </div>













                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/pickadate/picker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/picker.date.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/picker.time.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/legacy.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/js/showorder.js') }}"></script>
    <script  type="text/javascript">
"use strict";

        $('#user_id').select2({
            placeholder: "@lang('site.search-employers')...",
            minimumInputLength: 3,
            ajax: {
                url: '{{ route('admin.employers.search') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        term: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }

        });

        $('#candidates').select2({
            placeholder: "@lang('site.search-candidates')...",
            minimumInputLength: 3,
            ajax: {
                url: '{{ route('admin.candidates.search') }}?format=candidate_id',
                dataType: 'json',
                data: function (params) {
                    return {
                        term: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }

        });

    </script>
@endsection


@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <link href="{{ asset('vendor/pickadate/themes/default.date.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/pickadate/themes/default.time.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/pickadate/themes/default.css') }}" rel="stylesheet">


    @parent
    <link rel="stylesheet" href="{{ asset('css/admin/showorder.css') }}">


@endsection
