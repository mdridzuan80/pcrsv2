<div class="table-responsive">
    @foreach ($events as $event)
        @if ($event instanceof App\Cuti)
        <div class="callout callout-warning">
            <h4>CUTI UMUM : {{ $event->title }}</h4>
        </div>
        @endif

        @if ($tarikh->lessThanOrEqualTo(today()) && ($event instanceof App\FinalAttendance || $event instanceof App\Kehadiran || gettype($event) == 'array'))
            <div class="box box-success box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">CHECK-IN/ OUT</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                        <th>CHECK-IN</th>
                        <th>CHECK-OUT</th>
                        </tr>
                        <tr>
                        <td>{{ (explode(PHP_EOL, optional($event)->title)[0]) ? explode(':', explode(PHP_EOL, optional($event)->title)[0], 2)[1] : explode(':', explode(PHP_EOL, $event['title'])[0], 2)[1] }}</td>
                        <td>{{ explode(':', explode(PHP_EOL, optional($event)->title)[1], 2)[1] ?? explode(':', explode(PHP_EOL, $event['title'])[1], 2)[1] }}</td>
                        </tr>
                    </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        @endif

        @if ($event instanceof App\Acara)
            <div class="box box-danger box-solid">
                <div class="box-header with-border">
                <h3 class="box-title">Acara : {{ $event->title }}</h3>

                <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <td>JENIS ACARA</td>
                            <td>{{ $event->jenis_acara }}</td>
                        </tr>
                        <tr>
                            <td>MASA MULA</td>
                            <td>{{ $event->start }}</td>
                        </tr>
                        <tr>
                            <td>MASA TAMAT</td>
                            <td>{{ $event->end }}</td>
                        </tr>
                        <tr>
                            <td>KETERANGAN</td>
                            <td>{{ $event->keterangan }}</td>
                        </tr>
                    </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        @endif

    @endforeach
</div>