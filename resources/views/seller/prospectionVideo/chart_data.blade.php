<div class="row gy-4 mb-4">
    <div class="col-xxl-4 col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                    <h6 class="fs-18 font-circularxx">{{__('Full views')}}
                        <span class="badge fs-12 text-white p-1 fifth-graph-percentage"></span>
                    </h6>
                </div>
                <div id="full_graph_div">
                    <canvas id="full-graph" style="width:100%;max-width:500px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-4 col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                    <h6 class="fs-18 font-circularxx">{{__('Partial views')}}
                        <span class="badge fs-12 text-white p-1 first-graph-percentage"></span>
                    </h6>
                </div>
                <div id="partial_graph_div">
                    <canvas id="partial-graph" style="width:100%;max-width:500px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-4 col-lg-6">
        <div class="card">
            <div class="card-body p-4">
                <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                    <h6 class="fs-18 font-circularxx">{{__('Not played')}}
                        <span class="badge fs-12 text-white p-1 fourth-graph-percentage"></span>
                    </h6>
                </div>
                <div id="not_played_graph_div">
                    <canvas id="not-played-graph" style="width:100%;max-width:500px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>