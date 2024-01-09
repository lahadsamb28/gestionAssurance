import { Injectable } from '@angular/core';
import { FormGroup } from '@angular/forms';
import { BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class CustomvalidationService {
  message!: string;
  code!: number;
  visible = new BehaviorSubject<boolean>(true);
  constructor() { }

  MatchPassword(password: any, confirmpassword: any){
    return (formGroup: FormGroup) => {
      const passwordControl = formGroup.controls[password];
      const confirmpasswordControl = formGroup.controls[confirmpassword];

      if(!passwordControl || !confirmpasswordControl){
        return;
      }

      if(confirmpasswordControl.errors && !confirmpasswordControl.errors['Mismatch']){
        return;
      }

      if(passwordControl.value !== confirmpasswordControl.value){
        confirmpasswordControl.setErrors({ Mismatch: true });
      } else {
        confirmpasswordControl.setErrors(null);
      }
    }
  }


  setMessage(data: any){
    this.message = data
  }

  getMessage(){
    return this.message
  }
  setCode(status: number){
    this.code = status
  }
  getCode(){
    return this.code;
  }

  changeBarVisibility(value: boolean){
    this.visible.next(value);
  }
  checkBarVisibility(){
    return this.visible.asObservable();
  }

}
