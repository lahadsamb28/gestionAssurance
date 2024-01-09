import { HttpErrorResponse } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Profil } from 'src/app/models/user.model';
import { CustomvalidationService } from 'src/app/services/customvalidation.service';
import { UserServiceService } from 'src/app/services/user-service.service';

@Component({
  selector: 'app-profil',
  templateUrl: './profil.component.html',
  styleUrls: ['./profil.component.scss']
})
export class ProfilComponent implements OnInit{
  TypeOfUser: any = ['admin', 'simple'];
  Gender: any = ['HOMME', 'FEMME'];
  profil = new Profil()
  message: any;
  errMessage: any;
  code: any;
  id: any;
  loggedIn: boolean = false;

  constructor(private userService: UserServiceService, private router: Router, private route: ActivatedRoute, private custom: CustomvalidationService){}

  ngOnInit(): void {

    const routeParams = this.route.snapshot.paramMap;
    this.id = Number(routeParams.get('id'));

    this.getUser(this.id);

    this.custom.changeBarVisibility(false);
  }

  getUser(idUser: number){
    this.userService.edit(idUser).subscribe((data: any) => {
      this.profil.NIN = data.NIN
      this.profil.typeOfUser = data.typeOfUser
      this.profil.name = data.name
      this.profil.gender = data.gender
      this.profil.dateOfBirth = data.dateOfBirth
      this.profil.phone = data.phone
      this.profil.email = data.email
      this.profil.login = data.login
      this.message = data.message
    },
    (err: HttpErrorResponse) => {
      this.errMessage = err.error.message;
      this.code = err.status;
      console.log(this.errMessage, this.code);
    })
  }

  onUpdate(){
    this.userService.update(this.profil, this.id).subscribe((resp: any) => {
      this.message = resp.message;
      this.router.navigate(['/user/profil/'+this.id]);
    })
  }

}
