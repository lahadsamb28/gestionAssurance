import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { AttestationService } from 'src/app/services/attestation.service';
import { CustomvalidationService } from 'src/app/services/customvalidation.service';
import { UserServiceService } from 'src/app/services/user-service.service';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit{
  private id!: number;

  constructor(private userService: UserServiceService, private router: Router, private control: CustomvalidationService, private attestationService: AttestationService, private route: ActivatedRoute){}

  ngOnInit(): void {
    this.control.changeBarVisibility(true)

    const routeParams = this.route.snapshot.paramMap;
    this.id = Number(routeParams.get('id'));

    this.getPdf(this.id);

  }

  getPdf(idCert: number){
    this.attestationService.getPdf(idCert).subscribe((res) => {
      var fileUrl = URL.createObjectURL(res)
      window.open(fileUrl)
    })
  }


}
