<div class="candidates-content">
    <div class="candidates-header">
        <h1 class="section-title">Candidate Details</h1>
        <hr>
    </div>

    <?php
    function displayCandidates($position, $conn)
    {
        $query = "SELECT * FROM candidates WHERE status='Accepted' AND post='$position'";
        $query_run = mysqli_query($conn, $query);

        if (!$query_run) {
            echo "Error: " . mysqli_error($conn);
            return;
        }

        if (mysqli_num_rows($query_run) > 0) {
            echo "<div class='position-section'>
                    <h2 class='position-title'>$position</h2>
                    <hr>
                    <div class='candidates-grid'>";

            foreach ($query_run as $nominee) {
    ?>
                        <div class='candidate-card'>
                          <div class='candidate-image'>
                            <img src='<?=$nominee['pfp']?>' alt='<?=$nominee['name']?>' class='img-fluid'>
                          </div>
                          <div class='candidate-info'>
                            <h5 class='candidate-name'><?=$nominee['name']?></h5>
                            <p class='candidate-dept'><?=$nominee['dept']?></p>
                            <button type="button" class='btn btn-primary btn-sm' data-bs-toggle="modal" data-bs-target="#nomineeDetails<?=$nominee['id']?>">
                              View Candidate
                            </button>
                          </div>
                        </div>

                  <div class="modal fade" id="nomineeDetails<?=$nominee['id']?>" tabindex="-1"
                    aria-labelledby="nomineeDetailsLabel<?=$nominee['id']?>" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5"
                                    id="nomineeDetailsLabel<?=$nominee['id']?>">Nominee Details -
                                    <?=$nominee['name']?></h1>
                                <button type="button" class="btn-close"  data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="card text-start">
                                    <div class="card-body">
                                        <div class="row align-middle">
                                            <div class="col-lg-6 order-lg-2 d-flex align-items-center justify-content-center">
                                                <img src="<?=$nominee['pfp']?>" alt="<?=$nominee['pfp']?>" class="pfp">
                                            </div>
                                            <div class="col-lg-6 order-lg-1">
                                                <label class="form-label">Name of Nominee:</label>
                                                <p class="form-control"><?=$nominee['name']?></p>
                                                <label class="form-label">Department of Nominee:</label>
                                                <p class="form-control"><?=$nominee['dept']?></p>
                                                <label class="form-label">Average CGPA:</label>
                                                <p class="form-control"><?=$nominee['cgpa']?></p>
                                                <label class="form-label">Nominee for the Position of:</label>
                                                <p class="form-control"><?=$nominee['post']?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="form-label">Reason to nominate themselves for this position:</label>
                                                <p class="form-control"><?=$nominee['reason']?></p>
                                                <label class="form-label">Insights on Nominee:</label>
                                                <p class="form-control"><?=$nominee['detail']?></p>
                                                <label class="form-label">Club Participation:</label>
                                                <p class="form-control"><?=$nominee['club']?></p>
                                                <label class="form-label">Experiences and Achievements:</label>
                                                <p class="form-control"><?=$nominee['achieve']?></p>
                                                <img src="<?=$nominee['cert']?>" alt="<?=$nominee['cert']?>" class="cert">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>

    <?php
            }

            echo "</div>
                </div>";
        } else {
            echo "<div class='position-section'>
                    <h2 class='position-title'>$position</h2>
                    <hr>
                    <div class='empty-state'>
                      <i class='fas fa-users fa-3x text-muted mb-3'></i>
                      <h5 class='text-muted'>No $position applications accepted yet</h5>
                    </div>
                  </div>";
        }
    }

    // Define the positions in the order they should appear
    $positions = ['Cultural Secretary', 'General Secretary', 'Joint Secretary', 'Sports Secretary'];
    
    foreach ($positions as $position) {
        displayCandidates($position, $conn);
    }
    ?>
</div>

<style>
.candidates-content {
    padding: 0;
}

.candidates-header {
    margin-bottom: 30px;
}

.candidates-header h1 {
    color: #333;
    font-weight: 600;
    margin-bottom: 10px;
    text-align: left;
    font-size: 2rem;
}

.position-section {
    margin-bottom: 40px;
    padding: 0 10px;
}

.position-title {
    color: #333;
    font-weight: 700;
    margin-bottom: 15px;
    font-size: 1.5rem;
    text-align: left;
}

.candidates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    margin-top: 20px;
    justify-items: center;
}

.candidate-card {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    width: 100%;
    max-width: 300px;
    margin: 0 auto;
}

.candidate-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.candidate-image {
    margin-bottom: 15px;
}

.candidate-image img {
    width: 140px;
    height: 140px;
    border-radius: 12px;
    object-fit: cover;
    border: 3px solid #f8f9fa;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.candidate-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
    line-height: 1.3;
}

.candidate-dept {
    color: #666;
    margin-bottom: 20px;
    font-size: 0.95rem;
    font-weight: 500;
    line-height: 1.4;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    padding: 12px 24px;
    font-size: 0.95rem;
    border-radius: 8px;
    width: 100%;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

.empty-state {
    padding: 40px 20px;
    text-align: center;
}

.modal-content {
    border-radius: 8px;
    border: none;
}

.modal-header {
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
    border-radius: 8px 8px 0 0;
}

.pfp {
    height: 200px;
    width: 200px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #667eea;
}

.cert {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .candidates-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .candidate-card {
        padding: 20px;
        max-width: 100%;
    }
    
    .candidate-image img {
        width: 120px;
        height: 120px;
    }
    
    .position-title {
        font-size: 1.4rem;
    }
    
    .candidates-header h1 {
        font-size: 1.8rem;
    }
    
    .pfp {
        height: 150px;
        width: 150px;
    }
}

@media (max-width: 480px) {
    .candidate-card {
        padding: 15px;
    }
    
    .candidate-image img {
        width: 100px;
        height: 100px;
    }
    
    .candidate-name {
        font-size: 1.1rem;
    }
    
    .candidate-dept {
        font-size: 0.9rem;
    }
}
</style>